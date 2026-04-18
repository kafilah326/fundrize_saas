<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankFollowup extends Model
{
    use BelongsToTenant;

    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'content',
        'type',
        'followup_sequence',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
