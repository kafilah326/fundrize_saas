<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class QurbanTabunganSetting extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
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
