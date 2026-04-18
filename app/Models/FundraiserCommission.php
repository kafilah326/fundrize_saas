<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class FundraiserCommission extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
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
