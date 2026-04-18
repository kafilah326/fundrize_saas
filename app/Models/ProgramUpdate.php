<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramUpdate extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'program_id',
        'title',
        'description',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProgramUpdateImage::class);
    }
}
