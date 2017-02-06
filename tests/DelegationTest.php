<?php

use App\Committee;
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
        parent::teardown();
    }

    public function testCreate()
    {
        $this->actingAs(User::find(18));
        $user_1 = factory(App\User::class)->create([
            "id" => 102
        ])->archive()->save(factory(App\UserArchive::class)->create([
            'id' => 102,
            "Identity"=>"HEADDEL"
        ]));

        $this->visit("/create-delegation")
            ->type("第五测试代表团", "name")
            ->type("1", "delegate_number")
            ->select(102, "head_delegate_id")
            ->type("1", "ASS")//应该为双数
            ->press("现在提交")
            ->seePageIs("/create-delegation");
    }

    public function testChangeCommitteeLimit()
    {
        $this->actingAs(User::find(18));
        $this->visit("/committees/limit")
            ->type("2", "ASS")
            ->press("现在提交")
            ->seeInDatabase("committees", ['abbreviation' => 'ASS', 'limit' => '2']);
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
        $delegation_1->delete();
        $this->assertEquals("fail", SeatExchange::find($seat_exchange->id)->status);

    }

    public function testCache()
    {
        $this->actingAs(User::find(15));
        Cache::forget("delegations");
        Cache::forget("delegation_seats_count");
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
        foreach(Committee::all() as $committee){
            $seat_change_log[$committee->abbreviation] = 1;
        }
        $delegation->name = "改变后的测试代表团";
        $delegation->save();
        Event::fire(new \App\Events\DelegationUpdated($delegation,$seat_change_log));
        $this->assertEquals($seat_change_log, Cache::get("delegation_seats_count")[$delegation->id]);
        $this->assertEquals("改变后的测试代表团",Cache::get("delegations")[$delegation->id]->name);
    }


}
