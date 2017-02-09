<?php

namespace App\Listeners;

use App\Events\DelegationCreated;
use Auth;
use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Log;

/**
 * Class HandleDelegationCreated
 * Handle DelegationCreated Event
 * 1.Log that someone create a delegation in notice level
 * 2.update cache value of delegation_seat_count,creating a new key in it using the id of new-created delegation
 *
 * @package App\Listeners
 */
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
            $cache = new Collection();
            $cache[$delegation->id] = $delegation->committee_seats;
            Cache::put("delegation_seats_count", $cache, 24 * 60);
        }
    }
}
