<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user1_id', 'user2_id', 'dernier_message_at',
        'archived_by_user1', 'archived_by_user2',
    ];

    protected function casts(): array
    {
        return [
            'dernier_message_at' => 'datetime',
            'archived_by_user1'  => 'boolean',
            'archived_by_user2'  => 'boolean',
        ];
    }

    public function isArchivedFor(int $userId): bool
    {
        return ($this->user1_id === $userId && $this->archived_by_user1)
            || ($this->user2_id === $userId && $this->archived_by_user2);
    }

    public function user1()
    {
        return $this->belongsTo(User::class, 'user1_id');
    }

    public function user2()
    {
        return $this->belongsTo(User::class, 'user2_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function dernierMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function autreParticipant(int $userId): User
    {
        return $this->user1_id === $userId ? $this->user2 : $this->user1;
    }
}
