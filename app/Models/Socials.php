<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Socials extends Model
{
    protected $fillable = [
        'facebook',
        'whatsapp',
        'phone',
        'x',
        'instagram',
        'email',
        'linkedin',
        'logo',
    ];
}
