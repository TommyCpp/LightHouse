<?php

namespace App\Listeners;

use App\Events\SeatExchanged;
use App\Jobs\SendEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Request;

class HandleSeatExchanged
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
     * @param  SeatExchanged $event
     * @return void
     */
    public function handle(SeatExchanged $event)
    {
        //Log
        Log::notice("Seat Exchange has been finished", [
            'request_id' => $event->seat_exchange->id,
            'initiator' => $event->seat_exchange->initiator,
            'target' => $event->seat_exchange->target,
            'ip' => Request::ip(),
            'user-agent' => Request::header("User-Agent"),
            'user_name' => $event->user->name
        ]);
        dispatch(new SendEmail($event));

    }
}
