<?php

namespace App\Events;

use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageBroadcastEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Message
     *
     * @var Message
     */
    public $message;

    /**
     * Recipient user
     *
     * @var User
     */
    public $recipient;

     /**
     * Sender user
     *
     * @var User
     */
    public $sender;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $sender, User $recipient, Message $message)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $users = User::find([$this->sender->id, $this->recipient->id]);

        /**
         * chatId is the combination of recipient and sender id
         * ordered by id
         */
        $chatId = $users->pluck('id')->sort()->implode('-');

        return new PrivateChannel('chat.' . $chatId);
    }
}
