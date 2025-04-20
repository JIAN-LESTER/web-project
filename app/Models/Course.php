<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Course extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'courseID';

    protected $fillable = [
        'course_name'
    ];

    
    public function user(){
        return $this->hasMany(User::class, 'courseID', 'courseID');
    }

}
