<?php

namespace App\Events;

use App\Models\Mark;
use App\Models\Trip;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DelQueueEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $message;
    private int $trip_id;
    public Mark $mark;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $trip_id,string $message = null, Mark $mark = null)
    {
        $this->trip_id = $trip_id;
        $this->message = $message;
        $this->mark = $mark;
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
