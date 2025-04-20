<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $primaryKey = 'userID';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'userStatus',
        'courseID',
        'yearID',
        'avatar',
        'is_verified',
        'verification_token',
        'two_factor_code',
        'two_factor_expires_at',
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

    
    public function course(){
        return $this->belongsTo(Course::class, 'courseID', 'courseID');
    }

    public function year(){
        return $this->belongsTo(Year::class, 'yearID', 'yearID');
    }

    public function logs(){
        return $this->hasMany(Logs::class, 'userID', 'userID');
    }

    public function reports(){
        return $this->hasMany(Reports::class, 'userID', 'userID');
    }

    public function messages(){
        return $this->hasMany(Message::class, 'userID', 'userID');
    }

    public function conversations(){
        return $this->hasMany(Conversation::class, 'userID', 'userID');
    }


}
