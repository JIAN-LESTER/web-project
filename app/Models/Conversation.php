<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Conversation extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'conversationID';
    public $timestamp = false;

    
    protected $casts = [
        'sent_at' => 'datetime',
    ];

    protected $fillable = [
        'userID',
        'conversation_title',
        'conversation_status',
        'sent_at'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function message(){
        return $this->hasMany(Message::class, 'conversationID', 'conversationID');
    }

}
