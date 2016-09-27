<?php

use App\Delegation;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SeatExchangeTest extends TestCase
{
    use DatabaseTransactions;

    public function testProposeSeatExchange()
    {
        $this->actingAs(Delegation::find(20)->head_delegate);

        //发送请求
        $this->post("/delegation-seat-exchange", [
            'target' => 21,
            'ASS-in' => 0,
            'ASS-out' => 1,
            'CSCE-in' => 1,
            'CSCE-out' => 0,
            'SC-in' => 0,
            'SC-out' => 0,
            'AC-in' => 0,
            'AC-out' => 0,
            'HJS-in' => 0,
            'HJS-out' => 0,
            'UNDP-in' => 0,
            'UNDP-out' => 0,
            'G20-in' => 0,
            'G20-out' => 0,
            '_token' => csrf_token()])
            ->seeInDatabase("seat_exchanges", ['initiator' => 20, 'target' => 21, 'status' => 0])
            ->seeInDatabase("seat_exchange_records", ['committee_id' => 1, 'in' => 1]);

//        $this->actingAs(Delegation::find(21)->head_delegate);
//
//        $origin_ini = Delegation::find(20)->seats->count();
//        $origin_tar = Delegation::find(21)->seats->count();
//
//        $this->post("/delegation-seat-exchange", [
//            'target' => 20,
//            'ASS-in' => 1,
//            'ASS-out' => 0,
//            'CSCE-in' => 0,
//            'CSCE-out' => 1,
//            'SC-in' => 0,
//            'SC-out' => 0,
//            'AC-in' => 0,
//            'AC-out' => 0,
//            'HJS-in' => 0,
//            'HJS-out' => 0,
//            'UNDP-in' => 0,
//            'UNDP-out' => 0,
//            'G20-in' => 0,
//            'G20-out' => 0,
//            '_token' => csrf_token()])
//            ->
//            assertEquals($origin_ini + 1, Delegation::find(20)->seats->where("committee_id", 1)->count());
//
//        $this->assertEquals($origin_tar - 1, Delegation::find(21)->seats->where("committee_id", 2)->count());
    }
    
}
