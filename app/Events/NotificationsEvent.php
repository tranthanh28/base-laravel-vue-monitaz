<?php

namespace App\Events;

use App\Models\Core\Auth\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $noti_id;
    public $name;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $noti_id, $name)
    {
        $this->message = $message;
        $this->noti_id = $noti_id;
        $this->name = $name;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
//    public function broadcastOn()
//    {
//       return new PrivateChannel('test_event.'.$this->message->receiver_id);
//    }

    public function broadcastOn()
    {
        return ['notification-channel-'. $this->name];
    }

    public function broadcastAs()
    {
        return 'notification-event';
    }
}
