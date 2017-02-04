<?php

namespace App\Events;

use App\SeatExchange;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SeatExchangeApplied extends Event
{
    use SerializesModels;

    public $user;
    public $seat_exchange_apply;
    public $request;

    /**
     * Create a new event instance.
     *
     * @param SeatExchange $seat_exchange_apply
     * @param User $user
     * @param Request $request
     *
     */
    public function __construct(SeatExchange $seat_exchange_apply, User $user, Request $request)
    {
        $this->user = $user;
        $this->seat_exchange_apply = $seat_exchange_apply;
        $this->request = $request;
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
