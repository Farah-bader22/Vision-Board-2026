<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    
    protected $fillable = [
        'title',
        'category',
        'image_path',
        'progress',
        'user_id',
        'is_archived'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
