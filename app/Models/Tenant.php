<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'status',
        'trial_ends_at',
        'subscription_ends_at',
        'plan_id',
        'features',
        'limits',
        'notes',
    ];

    protected $casts = [
        'trial_ends_at' => 'date',
        'subscription_ends_at' => 'date',
        'features' => 'json',
        'limits' => 'json',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function domains()
    {
        return $this->hasMany(TenantDomain::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function addons()
    {
        return $this->hasMany(TenantAddon::class);
    }

    public function canUseCustomDomain()
    {
        if (!$this->plan) return false;
        return $this->plan->hasFeature('custom_domain');
    }

    public function getPlanName()
    {
        return $this->plan ? $this->plan->name : 'No Plan';
    }

    public function getSystemFeePercentage()
    {
        return $this->plan ? $this->plan->system_fee_percentage : config('system.system_fee_percentage', env('SYSTEM_FEE_PERCENTAGE', 5));
    }

    /**
     * Check if the tenant's plan includes a specific feature.
     */
    public function hasFeature(string $key): bool
    {
        // 1. Check Plan
        if ($this->plan && $this->plan->hasFeature($key)) {
            return true;
        }

        // 2. Check Active Add-ons
        return $this->addons()
            ->active()
            ->whereHas('addon', function ($query) use ($key) {
                $query->where('target', $key)->where('type', 'feature');
            })
            ->exists();
    }

    /**
     * Get a specific limit from the tenant's plan.
     */
    public function getLimit(string $key, $default = null)
    {
        $baseLimit = $this->plan ? $this->plan->getLimit($key, $default) : $default;

        // Sum up base limit + all active addon values for this limit type
        $addonValue = $this->addons()
            ->active()
            ->whereHas('addon', function ($query) use ($key) {
                $query->where('target', $key)->where('type', 'limit');
            })
            ->with('addon')
            ->get()
            ->sum(function ($tenantAddon) {
                return $tenantAddon->addon->value;
            });

        return $baseLimit + $addonValue;
    }

    /**
     * Check if the tenant can create more of a given resource based on plan limits.
     */
    public function canCreateMore(string $resource): bool
    {
        $limitMap = [
            'programs' => ['limit' => 'max_programs', 'model' => Program::class],
            'users'    => ['limit' => 'max_users',    'model' => User::class],
        ];

        if (!isset($limitMap[$resource])) return true;

        $config = $limitMap[$resource];
        $max = $this->getLimit($config['limit'], 99);
        $current = $config['model']::where('tenant_id', $this->id)->count();

        return $current < $max;
    }

    /**
     * Get the remaining count for a resource.
     */
    public function getRemainingQuota(string $resource): int
    {
        $limitMap = [
            'programs' => ['limit' => 'max_programs', 'model' => Program::class],
            'users'    => ['limit' => 'max_users',    'model' => User::class],
        ];

        if (!isset($limitMap[$resource])) return 99;

        $config = $limitMap[$resource];
        $max = $this->getLimit($config['limit'], 99);
        $current = $config['model']::where('tenant_id', $this->id)->count();

        return max(0, $max - $current);
    }
}
