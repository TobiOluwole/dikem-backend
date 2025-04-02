<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Events extends Model
{

    protected $fillable = ['slug', 'title', 'content', 'datetime', 'type', 'user_id', 'images' ];

    protected $casts = [
        'title' => 'json',
        'content' => 'json',
        'datetime' => 'datetime',
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

//    protected function getImageAttribute()
//    {
//        return  Storage::disk('public')->exists('events/' . $this->id . '.jpg')
//                ? asset('storage/events/' . $this->id . '.jpg')
//                : null;
//    }
}
