<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sections extends Model
{
    protected $fillable = ['page_id', 'type', 'content', 'type'];

    protected $casts = [
        'type' => 'json',
        'content' => 'json',
    ];

}

