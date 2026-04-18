<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Donation extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'transaction_id',
        'user_id',
        'fundraiser_id',
        'program_id',
        'amount',
        'package_quantity',
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

    public function fundraiser(): BelongsTo
    {
        return $this->belongsTo(Fundraiser::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'external_id', 'transaction_id');
    }

    public function fundraiserCommission()
    {
        return $this->morphOne(FundraiserCommission::class, 'commissionable');
    }
}
