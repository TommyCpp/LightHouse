<?php

namespace App\Events;

use App\Delegation;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DelegationUpdated extends Event
{
    use SerializesModels;
    public $seat_change_log;
    public $delegation;

    /**
     * Create a new event instance.
     *
     * @param Delegation $delegation
     * @param array $seat_change_log
     */
    public function __construct(Delegation $delegation,array $seat_change_log)
    {
        //
        $this->delegation = $delegation;
        $this->seat_change_log = $seat_change_log;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
