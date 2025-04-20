<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Categories extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'categoryID';

    protected $fillable = [
        'category_name',
    ];

    public function knowledgeBase()
    {
        return $this->hasMany(KnowledgeBase::class, 'categoryID');
    }
}
