<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = ['user_id', 'title', 'content', 'tags', 'visible', 'images', 'slug'];

    protected $casts = [
        'images' => 'array',
        'tags' => 'array',
        'title' => 'json',
        'content' => 'json',
        'visible' => 'boolean'
    ];
    protected $appends = [
        'display_image',
//        'tags'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayImageAttribute()
    {
        return is_array($this->images) && isset($this->images[0]) ? $this->images[0] : [];
    }

//    public function getTagsAttribute()
//    {
//        return is_array($this->tags) && isset($this->tags[0]) ? $this->tags : [];
//    }
}

