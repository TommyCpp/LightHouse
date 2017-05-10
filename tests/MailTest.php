<?php

use App\Jobs\SendEmail;
use App\Seat;
use App\SeatExchange;
use App\User;

class MailTest extends TestCase
{
//    use \MailThief\Testing\InteractsWithMail;
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    private $sendEmail = false;

    public function testMailConfig()
    {
        Mail::send(
            "emails/test", [], function ($message) {
            $message->to("receiver@test.com");
            $message->from("sender@test.com");
        });
        if ($this->sendEmail) {
            $this->seeMessageFor("receiver@test.com");
        }
    }

    public function testSeatExchangeAppliedNotifyEmail()
    {
        $delegation_1 = factory(App\Delegation::class, "mock")->create();
        $delegation_2 = factory(App\Delegation::class, "mock")->create();
        $seats = Seat::where("committee_id", 4)->where("is_distributed", 0)->take(2)->get();
        $delegation_1->seats()->saveMany($seats);
        $delegation_1->save();

        $seat_exchange_records = factory(\App\SeatExchangeRecord::class)->create(
            [
                "committee_id" => 4,
                "out" => 2,
                "in" => 0
            ]
        );
        $seat_exchange = factory(SeatExchange::class)->create([
            "id" => 101,
            "initiator" => $delegation_1->id,
            "target" => $delegation_2->id,
            "status" => 0,
        ]);
        $seat_exchange->seat_exchange_records()->save($seat_exchange_records);

        dispatch(new SendEmail(new \App\Events\SeatExchangeApplied($seat_exchange, User::find($delegation_1->id))));

    }

    public function testSeatExchangedNotifyEmail()
    {
        $delegation_1 = factory(App\Delegation::class, "mock")->create();
        $delegation_2 = factory(App\Delegation::class, "mock")->create();
        $seats = Seat::where("committee_id", 4)->where("is_distributed", 0)->take(2)->get();
        $delegation_1->seats()->saveMany($seats);
        $delegation_1->save();

        $seat_exchange_records = factory(\App\SeatExchangeRecord::class)->create(
            [
                "committee_id" => 4,
                "out" => 2,
                "in" => 0
            ]
        );
        $seat_exchange = factory(SeatExchange::class)->create([
            "id" => 101,
            "initiator" => $delegation_1->id,
            "target" => $delegation_2->id,
            "status" => 0,
        ]);
        $seat_exchange->seat_exchange_records()->save($seat_exchange_records);
        dispatch(new SendEmail(new \App\Events\SeatExchanged($seat_exchange, User::find($delegation_2->id))));
    }
}
