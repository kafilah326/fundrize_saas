<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QurbanSavingsDeposit extends Model
{
    protected $fillable = [
        'qurban_saving_id',
        'transaction_id',
        'amount',
        'total',
        'payment_method',
        'status',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function qurbanSaving(): BelongsTo
    {
        return $this->belongsTo(QurbanSaving::class, 'qurban_saving_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'external_id', 'transaction_id');
    }

    public function fundraiserCommission()
    {
        return $this->morphOne(FundraiserCommission::class, 'commissionable');
    }
}
