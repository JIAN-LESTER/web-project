<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Reports extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'reportID';

    protected $fillable = [
        'report_title',
        'report_type',
        'userID',
        'started_date',
        'end_date',
        'generated_at',

    ];

    public function user(){
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

}
