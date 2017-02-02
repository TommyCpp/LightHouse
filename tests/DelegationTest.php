<?php

use App\Seat;
use App\SeatExchange;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DelegationTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreate()
    {
        $this->actingAs(User::find(18));
        $this->visit("/create-delegation")
            ->type("第五测试代表团", "name")
            ->type("1", "delegate_number")
            ->select("22", "head_delegate_id")
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


}
