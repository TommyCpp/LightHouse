<?php

use App\Committee;
use App\Delegation;
use App\Seat;
use App\SeatExchange;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DelegationTest extends TestCase
{
    use DatabaseTransactions;

    public function teardown()
    {
        Cache::forget("delegations");
        Cache::forget("delegation_seats_count");
        Cache::forget("committees");
        parent::teardown();
    }

    public function setup(){
        parent::setup();
        Cache::forget("delegations");
        Cache::forget("delegation_seats_count");
        Cache::forget("committees");
    }

    public function testChangeCommitteeLimit()
    {
        $this->actingAs(User::find(18));
        $committee = factory(App\Committee::class,'mock')->create();
        $this->visit("/committees/limit")
            ->type("2", strtoupper($committee->abbreviation))
            ->press("现在提交")
            ->seeInDatabase("committees", ['abbreviation' => strtoupper($committee->abbreviation), 'limit' => '2']);
    }

    public function testCreate()
    {
        $this->actingAs(User::find(18));
        $delegation = factory(App\Delegation::class, 'mock')->make();

        $this->visit("/create-delegation")
            ->type($delegation->name, "name")
            ->type($delegation->delegate_number, "delegate_number")
            ->select($delegation->head_delegate->id, "head_delegate_id")
            ->type("1", "ASS")//应该为双数
            ->type(csrf_token(), "_token")
            ->press("现在提交")
            ->seePageIs("/create-delegation")
            ->notSeeInDatabase("delegations", ["id" => $delegation->id,
            ]);

        $this->visit("/create-delegation")
            ->type($delegation->name, "name")
            ->type($delegation->delegate_number, "delegate_number")
            ->select($delegation->head_delegate->id, "head_delegate_id")
            ->type($delegation->delegate_number, "HJS")
            ->type(csrf_token(), "_token")
            ->press("现在提交")
            ->seePageIs("/delegations")
            ->assertNotNull(Delegation::where("name",$delegation->name)->where("head_delegate_id",$delegation->head_delegate->id)->get());
    }

    public function testDelete()
    {
        parent::setup();
        $this->actingAs(User::find(15));
        $user_1 = factory(App\User::class)->create([
            "id" => 100
        ]);
        $user_2 = factory(App\User::class)->create([
            "id" => 101
        ]);
        $delegation_1 = factory(App\Delegation::class)->create([
            "id" => 100,
            "head_delegate_id" => 100
        ]);
        $seats = Seat::where("committee_id", 4)->where("is_distributed", 0)->take(2)->get();
        $delegation_1->seats()->saveMany($seats);
        $delegation_1->save();
        $this->assertEquals(2, $delegation_1->seats()->getResults()->count());

        $delegation_2 = factory(App\Delegation::class)->create([
            "id" => 101,
            "head_delegate_id" => 101
        ]);
        $seat_exchange = factory(App\SeatExchange::class)->create([
            "initiator" => 100,
            "target" => 101,
            "status" => "pending"
        ]);
        $this->assertEquals("pending", $seat_exchange->status);
        $this->assertArrayHasKey($delegation_1->id, Cache::get("delegations"));
        $delegation_1->delete();
        $this->assertEquals("fail", SeatExchange::find($seat_exchange->id)->status);
        $cache = Cache::get("delegations");
        $this->assertArrayNotHasKey($delegation_1->id, $cache);

    }

    public function testCache()
    {
        $this->actingAs(User::find(15));
        //清空相关Cache
        $delegation = factory(App\Delegation::class, 'mock')->create();
        Event::fire(new \App\Events\DelegationCreated($delegation));
        $this->assertNotNull(Cache::get("delegations"));//创建代表团后要更新Cache中的delegations，如果没有要创建
        $this->assertNotNull(Cache::get("delegation_seats_count"));
        $this->assertEquals($delegation->committee_seats, $delegation->rememberCommitteeSeats());

        //相关Cache非空的情况
        $delegation = factory(App\Delegation::class, 'mock')->create();
        Event::fire(new \App\Events\DelegationCreated($delegation));
        $this->assertNotNull(Cache::get("delegations"));//创建代表团后要更新Cache中的delegations，如果没有要创建
        $this->assertNotNull(Cache::get("delegation_seats_count"));
        $this->assertEquals($delegation->committee_seats, $delegation->rememberCommitteeSeats());

        $seat_change_log = [];
        foreach (Committee::all() as $committee) {
            $seat_change_log[$committee->abbreviation] = 1;
        }
        $delegation->name = "改变后的测试代表团";
        $delegation->save();
        Event::fire(new \App\Events\DelegationUpdated($delegation, $seat_change_log));
        $this->assertEquals($seat_change_log, Cache::get("delegation_seats_count")[$delegation->id]);
        $this->assertEquals("改变后的测试代表团", Cache::get("delegations")[$delegation->id]->name);
    }


}
