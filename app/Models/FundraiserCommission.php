<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundraiserCommission extends Model
{
    protected $fillable = [
        'fundraiser_id',
        'commissionable_type',
        'commissionable_id',
        'amount',
        'status',
    ];

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }

    public function commissionable()
    {
        return $this->morphTo();
    }
}
