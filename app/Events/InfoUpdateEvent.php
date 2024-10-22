<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InfoUpdateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private int $trip_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $trip_id)
    {
        $this->trip_id = $trip_id;
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn() : Channel
    {
        return new PrivateChannel('privateTrip.' . $this->trip_id);
    }
}
