<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Fundraiser extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'referral_code',
        'name',
        'whatsapp',
        'email',
        'address',
        'domicile',
        'status',
        'rejected_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function commissions()
    {
        return $this->hasMany(FundraiserCommission::class);
    }

    public function withdrawals()
    {
        return $this->hasMany(FundraiserWithdrawal::class);
    }

    public function banks()
    {
        return $this->hasMany(FundraiserBank::class);
    }

    public function visits()
    {
        return $this->hasMany(FundraiserVisit::class);
    }

    public function getAvailableBalanceAttribute()
    {
        $totalSuccess = $this->commissions()->where('status', 'success')->sum('amount');
        $totalWithdrawn = $this->withdrawals()->whereIn('status', ['pending', 'approved'])->sum('amount');
        return $totalSuccess - $totalWithdrawn;
    }
}
