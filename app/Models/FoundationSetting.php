<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoundationSetting extends Model
{
    protected $fillable = [
        'name',
        'tagline',
        'logo',
        'favicon',
        'about',
        'vision',
        'mission',
        'focus_areas',
        'address',
        'phone',
        'email',
        'social_media',
    ];

    protected $casts = [
        'mission' => 'array',
        'focus_areas' => 'array',
        'social_media' => 'array',
    ];
}
