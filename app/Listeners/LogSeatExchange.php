<?php

namespace App\Listeners;

use App\Events\SeatExchangeApplied;
use App\Jobs\SendEmailOnApplySeatExchange;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class LogSeatExchange
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
            "request_id" => $event->seat_exchange_apply->id,
            "initiator" => $event->seat_exchange_apply->initiator,
            "target" => $event->seat_exchange_apply->target,
            "ip" => $event->request->ip(),
            "user-agent" => $event->request->header("User-Agent"),
            "user_name" => $event->user->name
        ]);
        //向initiator和target发送相应邮件
        dispatch(new SendEmailOnApplySeatExchange($event));


    }
}
