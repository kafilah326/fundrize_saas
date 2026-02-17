<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramDistribution extends Model
{
    protected $fillable = [
        'program_id',
        'amount_distributed',
        'description',
        'documentation_date',
    ];

    protected $casts = [
        'amount_distributed' => 'decimal:2',
        'documentation_date' => 'date',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
