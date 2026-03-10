<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ZakatTransaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'zakat_type',
        'jumlah_jiwa',
        'total_harta',
        'nisab_at_time',
        'calculated_zakat',
        'amount',
        'admin_fee',
        'total',
        'donor_name',
        'donor_phone',
        'donor_email',
        'fundraiser_id',
        'payment_method',
        'status',
        'payment_expiry',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'admin_fee'        => 'decimal:2',
        'total'            => 'decimal:2',
        'total_harta'      => 'decimal:2',
        'nisab_at_time'    => 'decimal:2',
        'calculated_zakat' => 'decimal:2',
        'payment_expiry'   => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'external_id', 'transaction_id');
    }

    public function fundraiser(): BelongsTo
    {
        return $this->belongsTo(Fundraiser::class);
    }

    public function fundraiserCommission()
    {
        return $this->morphOne(FundraiserCommission::class, 'commissionable');
    }

    public function getZakatTypeLabelAttribute(): string
    {
        return match ($this->zakat_type) {
            'fitrah' => 'Zakat Fitrah',
            'maal'   => 'Zakat Mal',
            default  => ucfirst($this->zakat_type),
        };
    }
}
