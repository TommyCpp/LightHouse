<?php

namespace App\Events;

use App\SeatExchange;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class SeatExchangeApplied
 * @package App\Events
 *
 */
class SeatExchangeApplied extends Event
{
    use SerializesModels;

    public $user;
    public $seat_exchange;

    /**
     * Create a new event instance.
     *
     * @param SeatExchange $seat_exchange_apply
     * @param User $user
     * @internal param User $user
     * @internal param Request $request
     */
    public function __construct(SeatExchange $seat_exchange_apply, User $user)
    {
        $this->user = $user;
        $this->seat_exchange = $seat_exchange_apply;
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
