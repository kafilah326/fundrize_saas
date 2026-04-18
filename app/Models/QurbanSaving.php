<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QurbanSaving extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'fundraiser_id',
        'target_animal_type',
        'target_amount',
        'saved_amount',
        'target_hijri_year',
        'donor_name',
        'whatsapp',
        'qurban_name',
        'reminder_enabled',
        'reminder_frequency',
        'status',
        'created_at',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'saved_amount' => 'decimal:2',
        'reminder_enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(QurbanSavingsDeposit::class, 'qurban_saving_id');
    }

    public function getProgressAttribute()
    {
        if ($this->target_amount <= 0) return 0;
        return min(100, round(($this->saved_amount / $this->target_amount) * 100));
    }

    public function documentations()
    {
        return $this->morphMany(QurbanDocumentation::class, 'documentable');
    }

    public function fundraiserCommission()
    {
        return $this->morphOne(FundraiserCommission::class, 'commissionable');
    }
}
