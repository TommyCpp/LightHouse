<?php

namespace App\Events;

use App\Delegation;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DelegationCreated extends Event
{
    use SerializesModels;

    public $delegation;
    /**
     * Create a new event instance.
     *
     * @param Delegation $delegation
     */
    public function __construct(Delegation $delegation)
    {
        //
        $this->delegation = $delegation;
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
