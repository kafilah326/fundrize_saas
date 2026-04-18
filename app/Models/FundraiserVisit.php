<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class FundraiserVisit extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'fundraiser_id',
        'ip_address',
        'user_agent',
        'url_visited',
    ];

    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }
}
