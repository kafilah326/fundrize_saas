<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class FundraiserBank extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'fundraiser_id',
        'bank_name',
        'account_number',
        'account_name',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }
}
