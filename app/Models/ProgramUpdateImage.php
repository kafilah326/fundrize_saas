<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramUpdateImage extends Model
{
    protected $fillable = [
        'program_update_id',
        'image_url',
        'sort_order',
    ];

    public function programUpdate(): BelongsTo
    {
        return $this->belongsTo(ProgramUpdate::class, 'program_update_id');
    }
}
