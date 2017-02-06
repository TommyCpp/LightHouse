<?php

namespace App\Listeners;

use App\Events\DelegationUpdated;
use Illuminate\Support\Collection;
use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleDelegationUpdated
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
     * @param  DelegationUpdated $event
     * @return void
     */
    public function handle(DelegationUpdated $event)
    {
        //
        //更新delegation_seats_count
        $delegation = $event->delegation;
        $seat_change_log = $event->seat_change_log;
        if (Cache::has("delegation_seats_count")) {
            $cache = Cache::get("delegation_seats_count");
            if (array_has($cache, $delegation->id)) {
                $committee_seat = $cache[$delegation->id];
                foreach ($committee_seat as $abbr => $count) {
                    $committee_seat[$abbr] = $count + $seat_change_log[$abbr];
                }
                $cache[$delegation->id] = $committee_seat;
                Cache::put("delegation_seats_count", $cache, 24 * 60);
            }
        } else {
            $cache = new Collection();
            $cache[$delegation->id] = $delegation->committee_seats;
            Cache::put("delegation_seats_count", $cache, 24 * 60);
        }
        //更新delegations
        if (Cache::has("delegations")) {
            $cache = Cache::get("delegations");
            if (array_has($cache, $delegation->id)) {
                $cache[$delegation->id] = $delegation;
            } else {
                $cache[$delegation->id] = $delegation;
            }
            Cache::put("delegations", $cache, 24 * 60);
        } else {
            $cache = new Collection();
            $cache[$delegation->id] = $delegation;
            Cache::put("delegations", $cache, 24 * 60);
        }
    }
}
