<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\Message;

class NewMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    private $groupUsersIds;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message, $groupUsersIds)
    {
        $this->message = $message;
        $this->groupUsersIds = $groupUsersIds;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = [];
        if ($this->groupUsersIds) {
            foreach ($this->groupUsersIds as $id) {
                array_push($channels, new PrivateChannel('chat.' . $id));
            }
        } else {
            array_push($channels, new PrivateChannel('chat.' . $this->message->to_id));
        }

        return $channels;
    }
}
