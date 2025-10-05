<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // ★ DMとフォロー機能に必要
use Illuminate\Database\Eloquent\Relations\HasMany; // ★ メッセージ機能に必要

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // ツイート機能
    public function tweets(): HasMany
    {
        return $this->hasMany(Tweet::class);
    }

    // いいね機能
    public function likes(): BelongsToMany
    {
       return $this->belongsToMany(Tweet::class)->withTimestamps();
    }

    // コメント機能
    public function comments(): HasMany
    {
      return $this->hasMany(Comment::class);
    }

    // フォロー機能 (自分がフォローしている人)
    public function follows(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follow_id', 'follower_id');
    }

    // フォロワー機能 (自分をフォローしている人)
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'follow_id');
    }
    
    // --- DM機能 ---
    
    /**
     * このユーザーが参加しているDMルーム
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class);
    }

    /**
     * このユーザーが送信したメッセージ
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
