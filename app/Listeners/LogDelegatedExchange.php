<?php

namespace App\Listeners;

use App\Events\DelegateExchangeApplied;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class LogDelegatedExchange
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
     * @param  DelegateExchangeApplied $event
     * @return void
     */
    public function handle(DelegateExchangeApplied $event)
    {
        Log::info("Seat Exchange Request has been filed", [
            "request_id" => $event->seat_exchange_record->id,
            "initiator" => $event->seat_exchange_record->initiator,
            "target" => $event->seat_exchange_record->target,
            "ip" => $event->request->ip(),
            "user-agent" => $event->request->header("User-Agent"),
            "user_name" => $event->user->name
        ]);

    }
}
