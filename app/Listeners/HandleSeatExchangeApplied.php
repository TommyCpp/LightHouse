<?php

namespace App\Listeners;

use App\Events\SeatExchangeApplied;
use App\Jobs\SendEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Request;

class HandleSeatExchangeApplied
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param SeatExchangeApplied $event
     */
    public function handle(SeatExchangeApplied $event)
    {
        Log::info("Seat Exchange Request has been filed", [
            "request_id" => $event->seat_exchange->id,
            "initiator" => $event->seat_exchange->initiator,
            "target" => $event->seat_exchange->target,
            "ip" => Request::ip(),
            "user-agent" => Request::header("User-Agent"),
            "user_name" => $event->user->name
        ]);
        //向initiator和target发送相应邮件
        dispatch(new SendEmail($event));


    }
}
