<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projects extends Model
{
    protected $fillable = ['user_id', 'title', 'content', 'completed', 'images', 'slug'];

    protected $casts = [
        'images' => 'array',
        'title' => 'json',
        'content' => 'json',
        'completed' => 'boolean'
    ];

    protected $appends = [
        'display_image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayImageAttribute()
    {
        return is_array($this->images) && isset($this->images[0]) ? $this->images[0] : null;
    }
}

