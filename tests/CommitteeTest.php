<?php

use App\Committee;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;//不影响数据库
use Illuminate\Support\Facades\Cache;

class CommitteeTest extends TestCase
{
    use DatabaseTransactions;
    private $committee_id = 100;

    public function setUp()
    {
        parent::setup();
        Cache::forget("committees");
        Cache::forget("delegations");
        Cache::forget("delegation_seats_count");
    }

    /**
     *测试创建委员会表单
     */
    public function testCreateCommittee()
    {
        $this->actingAs(User::find(15));
        $delegation = factory(App\Delegation::class)->create();
        Event::fire(new \App\Events\DelegationCreated($delegation));
        $this->assertArrayHasKey($delegation->id, Cache::get("delegation_seats_count"));
        $this->assertArrayNotHasKey($this->committee_id, Cache::get("delegation_seats_count")[$delegation->id]);
        $this->visit("/create-committee")
            ->type($this->committee_id, "id")
            ->type("测试委员会", "chinese_name")
            ->type("Security Council", "english_name")
            ->select("1", "delegation")
            ->select("chinese", "language")
            ->type("SC", "abbreviation")
            ->type("24", "number")
            ->type("无议题", "topic_chinese_name")
            ->type("No Topic", "topic_english_name")
            ->type("Testing Note", "note")
            ->press("现在提交")
            ->seeInDatabase("committees", ['id' => $this->committee_id]);
        $this->assertArrayHasKey(Committee::find($this->committee_id)->abbreviation, Cache::get("delegation_seats_count")[$delegation->id]);

    }

    public function testReadNote()
    {
        $this->actingAs(User::find(15));
        factory(App\Committee::class)->create([
            "id" => $this->committee_id,
            "note" => "Testing Note"
        ]);
        $this->get('/committee/' . $this->committee_id . '/note', ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
            ->see("Testing Note");
    }

    public function testUpdateCommittee()
    {
        $this->actingAs(User::find(15));
        factory(App\Committee::class)->create([
            "id" => $this->committee_id,
            "note" => "Testing Note",
            "abbreviation" => "TEST"
        ]);
        $delegation = factory(App\Delegation::class, 'mock')->create();
        Event::fire(new \App\Events\DelegationCreated($delegation));
        $this->assertArrayHasKey("TEST", Cache::get("delegation_seats_count")[$delegation->id]);

        $this->visit("/committee/" . $this->committee_id . "/edit")
            ->type("修改后的测试议题", "topic_chinese_name")
            ->type("修改后的备注", "note")
            ->type("TESTAFTER", "abbreviation")
            ->press("现在提交")
            ->seePageIs("/committees")
            ->seeInDatabase("committees", ['id' => $this->committee_id, "topic_chinese_name" => "修改后的测试议题", "note" => "修改后的备注"]);

        $this->assertTrue(Cache::has("delegation_seats_count"));
        $this->assertArrayHasKey("TESTAFTER", Cache::get("delegation_seats_count")[$delegation->id],Cache::get("delegation_seats_count"));
        $this->assertArrayNotHasKey("TEST", Cache::get("delegation_seats_count")[$delegation->id]);
    }

    public function testDeleteCommittee()
    {
        Session::start();
        $this->actingAs(User::find(15));
        $committee = factory(App\Committee::class)->create([
            "id" => $this->committee_id,
            "note" => "Testing Note",
            "abbreviation" => "TEST"
        ]);
        $delegation = factory(App\Delegation::class, 'mock')->create();
        Event::fire(new \App\Events\DelegationCreated($delegation));
        $this->assertArrayHasKey("TEST", Cache::get("delegation_seats_count")[$delegation->id]);
        $this->post("/committee/" . $this->committee_id, ["_method" => 'DELETE', "_token" => csrf_token()])
            ->dontSeeInDatabase("committees", ['id' => $this->committee_id]);
        $this->assertTrue(Cache::has("delegation_seats_count"));
        $this->assertArrayNotHasKey("TEST", Cache::get("delegation_seats_count")[$delegation->id]);

    }

    public function testCommitteeSeats()
    {
        $this->actingAs(User::find(22));
        $this->post('/committee/1/seats', ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
            ->seeStatusCode(200);
    }

    public function testCache()
    {
        $this->actingAs(User::find(15));
        $committee = factory(App\Committee::class)->make();
        $committee->save();
        $this->assertNotNull(Cache::get("committees"));
        $this->assertEquals($committee->english_name, Cache::get("committees")[$committee->id]->english_name);
        /** @var \App\Committee $committee */
        $committee = \App\Committee::find($committee->id);
        $committee->update([
            "note" => "This is a new note"
        ]);
        $this->assertEquals('This is a new note', Cache::get("committees")->get($committee->id)->note);
        $committee->delete();
        $this->assertArrayNotHasKey($committee->id, Cache::get("committees")->toArray());
    }

    public function testCommitteeModel()
    {
        $this->actingAs(User::find(15));
        $committee = factory(App\Committee::class, 'mock')->create();
        $this->seeInDatabase("seats", ["committee_id" => $committee->id, "is_distributed" => 0]);
    }
}
