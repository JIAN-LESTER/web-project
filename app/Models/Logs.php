<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Logs extends Model
{
    use HasFactory, Notifiable;

    public $timestamps = false;
    protected $primaryKey = 'logID';

    protected $fillable = [
        'userID',
        'messageID',
        'action_type',
        
    ];

    public function user(){
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function message(){
        return $this->belongsTo(Message::class, 'messageID', 'messageID');
    }
}
