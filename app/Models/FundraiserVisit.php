<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FundraiserVisit extends Model
{
    protected $fillable = [
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
