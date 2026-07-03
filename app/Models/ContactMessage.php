<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sender_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'territory',
        'type',
        'sender_territory',
        'status',
        'reply',
        'replied_by',
        'replied_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'replied_at' => 'datetime',
    ];

    /**
     * Get the user who sent the message (if authenticated).
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the officer who replied to the message.
     */
    public function replier()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}
