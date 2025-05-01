<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class KnowledgeBase extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'kbID';
    public $timestamp = false;

    protected $fillable = [
        'kb_title',
        'content',
        'embedding',
        'categoryID',
        'source',

    ];

    public function category(){
        return $this->belongsTo(Categories::class, 'categoryID', 'categoryID');
    }

    public function message(){
        return $this->hasMany(Message::class, 'kbID', 'kbID');
    }

    public static function matchAnswer($question)
    {
        return self::where('question', 'LIKE', "%$question%")->first();
    }

}
