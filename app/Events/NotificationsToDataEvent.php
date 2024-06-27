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

class NotificationsToDataEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $noti_id;
    public $uid;
    public $nameChannel;
    public $competior;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $notiId, $uid, $nameChannel, $competior = false)
    {
        $this->message = $message;
        $this->noti_id = $notiId;
        $this->uid = $uid;
        $this->nameChannel = $nameChannel;
        $this->competior = $competior;
        \Log::info('create event '. $this->nameChannel . '- competitor: '.$this->competior);
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
        \Log::info('pusher to '. $this->nameChannel . '- competitor: '.$this->competior);
        return ['notification-data-'. $this->nameChannel];
    }

    public function broadcastAs()
    {
        return 'notification-data-event';
    }
}
