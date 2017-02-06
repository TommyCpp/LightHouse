<?php

namespace App\Listeners;

use App\Events\DelegationCreated;
use Auth;
use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class HandleDelegationCreated
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
     * @param  DelegationCreated $event
     * @return void
     */
    public function handle(DelegationCreated $event)
    {
        //
        $delegation = $event->delegation;
        Log::notice("New Delegation has been created", [
            "operator" => Auth::user()->name,
            "delegation_name" => $delegation->name
        ]);
        //Cache committee_seats info
        if (Cache::has("delegation_seats_count")) {
            $cache = Cache::get("delegation_seats_count");
            $cache[$delegation->id] = $delegation->committee_seats;
            Cache::put("delegation_seats_count", $cache, 24 * 60);
        } else {
            $cache[$delegation->id] = $delegation->committee_seats;
            Cache::put("delegation_seats_count", $cache, 24 * 60);
        }
    }
}
