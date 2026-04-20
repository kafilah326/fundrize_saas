<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\TenantDomain;
use App\Models\User;
use App\Models\FoundationSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantProvisioningService
{
    /**
     * Provision a new tenant with initial data and admin user
     */
    public function provision(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Create Tenant
            $slug = isset($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']);
            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (Tenant::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            $planId = $data['plan_id'] ?? \App\Models\Plan::where('slug', 'free')->value('id');

            $tenant = Tenant::create([
                'name' => $data['name'],
                'slug' => $slug,
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(config('tenancy.trial_days', 14)),
                'plan_id' => $planId,
            ]);

            // 2. Create Domain Mapping
            TenantDomain::create([
                'tenant_id' => $tenant->id,
                'domain' => $slug . '.' . config('tenancy.base_domain'),
                'type' => 'subdomain',
                'is_primary' => true,
            ]);

            // Set current tenant context for scoped models
            app()->instance('current_tenant', $tenant);

            // 3. Create Admin User
            User::create([
                'name' => 'Admin ' . $tenant->name,
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'admin',
                'tenant_id' => $tenant->id,
            ]);

            // 4. Create Foundation Settings
            FoundationSetting::create([
                'tenant_id' => $tenant->id,
                'name' => $tenant->name,
                'email' => $tenant->email,
                'phone' => $tenant->phone,
                'address' => 'Silakan lengkapi alamat yayasan',
            ]);

            // Can add AppSetting default population later here.

            // Reset current tenant context just in case (optional depending on use case)
            // app()->forgetInstance('current_tenant');

            return $tenant;
        });
    }
}
