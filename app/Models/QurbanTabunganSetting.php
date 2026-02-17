<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QurbanTabunganSetting extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'benefits',
        'description',
        'akad_title',
        'akad_description',
        'terms',
    ];

    protected $casts = [
        'benefits' => 'array',
        'terms' => 'array',
    ];
}
