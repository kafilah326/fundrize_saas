<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donation extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'program_id',
        'amount',
        'admin_fee',
        'total',
        'donor_name',
        'donor_phone',
        'donor_email',
        'is_anonymous',
        'doa',
        'payment_method',
        'status',
        'payment_expiry',
        'created_at', // Allow mass assignment for seeder
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'total' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'payment_expiry' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'external_id', 'transaction_id');
    }
}
