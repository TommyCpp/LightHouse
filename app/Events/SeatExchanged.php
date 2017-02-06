<?php

namespace App\Events;

use App\Events\Event;
use App\Seat;
use App\SeatExchange;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SeatExchanged extends Event
{
    use SerializesModels;

    /**
     * @var SeatExchange $seat_exchange
     */
    public $seat_exchange;
    /**
     * @var User $user
     */
    public $user;
    /**
     * Create a new event instance.
     *
     * @param SeatExchange $seat_exchange
     * @param User $user
     */
    public function __construct(SeatExchange $seat_exchange,User $user)
    {
        //
        $this->seat_exchange = $seat_exchange;
        $this->user = $user;
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
