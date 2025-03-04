<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Announcement extends Model
{

    protected $fillable = ['slug', 'title', 'content', 'visible', 'user_id' ];

    protected $casts = [
        'title' => 'json',
        'content' => 'json',
        'visible' => 'boolean',
    ];

    protected $appends = ['image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function getImageAttribute()
    {
        return  Storage::disk('public')->exists('announcements/' . $this->id . '.jpg')
                ? asset('storage/announcements/' . $this->id . '.jpg')
                : null;
    }
}
