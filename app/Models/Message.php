<?php

namespace App\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use BroadcastsEvents;

    protected $guarded = [];

    /**
     * @return BelongsTo
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chat_id');
    }

    /**
     * @param $event
     * @return array
     */
    public function broadcastOn($event): array
    {
        return match ($event) {
            "created" => [new PrivateChannel('chats.' . $this->chat->id)],
            default => [$this]
        };
    }

}
