<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model
{

    protected $fillable = ['slug', 'title', 'content', 'visible', 'user_id', 'images' ];

    protected $casts = [
        'title' => 'json',
        'content' => 'json',
        'visible' => 'boolean',
        'images' => 'json'
    ];

    protected $appends = ['display_image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayImageAttribute()
    {
        return is_array($this->images) && isset($this->images[0]) ? $this->images[0] : null;
    }


//    public function getImagesAttribute()
//    {
//        return isset($this->images) && is_array($this->images) ? $this->images : [];
//    }
}
