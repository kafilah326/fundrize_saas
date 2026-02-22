<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankFollowup extends Model
{
    use HasFactory;

    protected $fillable = [
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
