<?php

use App\Delegation;
use App\Seat;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SeatExchangeTest extends TestCase
{
    use DatabaseTransactions;

    public function setup()
    {
        parent::setup();
        Cache::forget("delegations");
        Cache::forget("committees");
        Cache::forget("delegation_seats_count");
    }

    public function teardown()
    {
        Cache::forget("committees");
        Cache::forget("delegations");
        Cache::forget("delegation_seats_count");
        parent::teardown();
    }

    public function testProposeSeatExchange()
    {

        $delegation_1 = factory(Delegation::class, "mock")->create();
        $delegation_2 = factory(Delegation::class, "mock")->create();
        //为delegation_1分配席位
        $seats = Seat::where("committee_id", 4)->where("is_distributed", 0)->take(2)->get();
        $delegation_1->seats()->saveMany($seats);
        $delegation_1->save();


        $this->actingAs(User::find($delegation_1->head_delegate->id));

        //发送请求
        $this->post("/delegation-seat-exchange", [
            'target' => $delegation_2->id,
            'ASS-in' => 0,
            'ASS-out' => 0,
            'CSCE-in' => 0,
            'CSCE-out' => 0,
            'SC-in' => 0,
            'SC-out' => 0,
            'AC-in' => 0,
            'AC-out' => 2,
            'HJS-in' => 0,
            'HJS-out' => 0,
            'UNDP-in' => 0,
            'UNDP-out' => 0,
            'G20-in' => 0,
            'G20-out' => 0,
            '_token' => csrf_token()])
            ->seeInDatabase("seat_exchanges", ['initiator' => $delegation_1->id, 'target' => $delegation_2->id, 'status' => 0])
            ->seeInDatabase("seat_exchange_records", ['committee_id' => 4, 'out' => 2])
            ->notSeeInDatabase("seats", [
                "delegation_id" => $delegation_2->id
            ]);


        $this->actingAs(User::find($delegation_2->head_delegate->id));


        $this->post("/delegation-seat-exchange", [
            'target' => $delegation_1->id,
            'ASS-in' => 0,
            'ASS-out' => 0,
            'CSCE-in' => 0,
            'CSCE-out' => 0,
            'SC-in' => 0,
            'SC-out' => 0,
            'AC-in' => 2,
            'AC-out' => 0,
            'HJS-in' => 0,
            'HJS-out' => 0,
            'UNDP-in' => 0,
            'UNDP-out' => 0,
            'G20-in' => 0,
            'G20-out' => 0,
            '_token' => csrf_token()])
            ->assertResponseOk()
            ->seeInDatabase("seat_exchanges", [
                'initiator' => $delegation_1->id,
                'target' => $delegation_2->id,
                'status' => 1
            ])
            ->seeInDatabase("seats", [
                'delegation_id' => $delegation_2->id
            ]);
    }

}
