<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSentCard implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $cards;
    public $alreadySent;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user, $cards, $alreadySent)
    {
        $this->user  = $user;
        $this->cards = $cards;
        $this->alreadySent = $alreadySent;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('game.'.$this->user->in_game);
    }

    public function broadcastWith()
    {
        return [
            'user_id' => $this->user->id,
            'cards'   => json_encode($this->cards),
            'alreadySent' => $this->alreadySent ? 1 : 0
        ];
    }
}
