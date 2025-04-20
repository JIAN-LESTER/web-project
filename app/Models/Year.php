<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Year extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'userID';

    protected $fillable = [
        'year_level',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

}
