<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['author_name', 'photo_path', 'content', 'is_published', 'sort_order'];

    protected $casts = [
        'is_published' => 'boolean',
    ];
}
