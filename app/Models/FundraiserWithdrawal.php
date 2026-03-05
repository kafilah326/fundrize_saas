<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundraiserWithdrawal extends Model
{
    protected $fillable = [
        'fundraiser_id',
        'amount',
        'bank_name',
        'account_number',
        'account_name',
        'status',
        'rejected_reason',
        'receipt_image',
        'processed_at',
    ];

    protected $casts = [
        'processed_at' => 'datetime',
    ];

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }
}
