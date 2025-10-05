<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    // content, room_id, user_id は Mass Assignmentで代入可能にする
    protected $fillable = [
        'content', // <-- ここを 'content' に修正
        'room_id',
        'user_id',
    ];

    /**
     * このメッセージの送信者
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * このメッセージが属するルーム
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
