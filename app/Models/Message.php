<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Message extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'messageID';
    public $timestamp = false;

    protected $fillable = [
        'userID',
        'kbID',
        'conversationID',
        'content',
        'message_status',
        'sender',
        'message_type',
        'sent_at',
        'responded_at',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function conversation(){
        return $this->belongsTo(Conversation::class, 'conversationID', 'conversationID');
    }

    public function knowledge_base(){
        return $this->belongsTo(KnowledgeBase::class, 'kbID', 'kbID');
    }

    public function logs(){
        return $this->hasMany(Logs::class, 'messageID', 'messageID');
    }

}
