<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use App\Models\SaasTransaction;
use App\Services\DuitkuService;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Paket Langganan')]
#[Layout('layouts.admin')]
class Subscription extends Component
{
    public $currentTenant;
    public $plans;
    public $allFeatures;
    public $usage;
    public $availableAddons;
    public $activeAddons;

    public function mount()
    {
        $this->currentTenant = app('current_tenant');
        $this->plans = Plan::active()->orderBy('sort_order')->get();
        
        $this->allFeatures = [
            'dynamic_program' => 'Program Dinamis (Paket)',
            'custom_domain' => 'Custom Domain Support',
            'whatsapp' => 'WhatsApp Notification',
            'fundraiser' => 'Manajemen Fundraiser',
            'qurban' => 'Modul Qurban',
            'zakat' => 'Modul Zakat',
        ];

        $this->availableAddons = \App\Models\Addon::where('is_active', true)->orderBy('sort_order')->get();
        $this->activeAddons = $this->currentTenant->addons()->active()->get();

        $this->calculateUsage();
    }

    private function calculateUsage()
    {
        $this->usage = [
            'programs' => [
                'current' => \App\Models\Program::where('tenant_id', $this->currentTenant->id)->count(),
                'max' => $this->currentTenant->getLimit('max_programs', 3),
            ],
            'users' => [
                'current' => \App\Models\User::where('tenant_id', $this->currentTenant->id)->count(),
                'max' => $this->currentTenant->getLimit('max_users', 2),
            ],
            // Storage is harder to calculate accurately without scanning files, usually we can keep it as info for now
            'storage' => [
                'current' => 0, // Placeholder
                'max' => $this->currentTenant->getLimit('storage_mb', 250),
            ]
        ];
    }

    public function upgrade($planId)
    {
        $plan = Plan::findOrFail($planId);
        
        if ($plan->id === $this->currentTenant->plan_id) {
            session()->flash('error', 'Anda sudah menggunakan paket ini.');
            return;
        }

        if ($plan->price <= 0) {
            // If upgrading to a free plan (unlikely for upgrade, but handle anyway)
            $this->currentTenant->update(['plan_id' => $plan->id]);
            session()->flash('success', 'Paket berhasil diperbarui.');
            return redirect()->route('admin.subscription');
        }

        // Online Payment via Duitku
        $externalId = 'SUB-' . time() . '-' . $this->currentTenant->id . '-' . $plan->id;
        
        $duitkuService = app(DuitkuService::class);
        $response = $duitkuService->createInvoice([
            'paymentAmount' => (int) $plan->price,
            'merchantOrderId' => $externalId,
            'productDetails' => 'Upgrade Paket: ' . $plan->name,
            'email' => auth()->user()->email,
            'customerDetail' => [
                'firstName' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phoneNumber' => auth()->user()->phone ?? '',
            ],
        ]);

        if (isset($response['paymentUrl'])) {
            // Log transaction
            SaasTransaction::create([
                'tenant_id' => $this->currentTenant->id,
                'external_id' => $externalId,
                'type' => 'subscription_upgrade',
                'amount' => $plan->price,
                'status' => 'pending',
                'metadata' => ['plan_id' => $plan->id],
            ]);

            return redirect()->away($response['paymentUrl']);
        }

        session()->flash('error', 'Gagal membuat invoice pembayaran: ' . ($response['statusMessage'] ?? 'Unknown Error'));
    }

    public function buyAddon($addonId)
    {
        $addon = \App\Models\Addon::findOrFail($addonId);

        // Online Payment via Duitku
        $externalId = 'ADDON-' . time() . '-' . $this->currentTenant->id . '-' . $addon->id;
        
        $duitkuService = app(DuitkuService::class);
        $response = $duitkuService->createInvoice([
            'paymentAmount' => (int) $addon->price,
            'merchantOrderId' => $externalId,
            'productDetails' => 'Pembelian Add-on: ' . $addon->name,
            'email' => auth()->user()->email,
            'customerDetail' => [
                'firstName' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phoneNumber' => auth()->user()->phone ?? '',
            ],
        ]);

        if (isset($response['paymentUrl'])) {
            // Log transaction
            SaasTransaction::create([
                'tenant_id' => $this->currentTenant->id,
                'external_id' => $externalId,
                'type' => 'addon_purchase',
                'amount' => $addon->price,
                'status' => 'pending',
                'metadata' => ['addon_id' => $addon->id],
            ]);

            return redirect()->away($response['paymentUrl']);
        }

        session()->flash('error', 'Gagal membuat invoice pembayaran: ' . ($response['statusMessage'] ?? 'Unknown Error'));
    }

    public function simulateAddon($addonId)
    {
        if (config('app.env') !== 'local') {
            return;
        }

        $addon = \App\Models\Addon::findOrFail($addonId);
        
        $expiresAt = null;
        if ($addon->duration === 'monthly') {
            $expiresAt = now()->addMonth();
        }

        \App\Models\TenantAddon::create([
            'tenant_id' => $this->currentTenant->id,
            'addon_id' => $addon->id,
            'purchased_at' => now(),
            'expires_at' => $expiresAt,
            'status' => 'active',
            'amount_paid' => $addon->price,
        ]);

        session()->flash('success', "Simulasi Berhasil: Add-on {$addon->name} telah diaktifkan.");
        return redirect()->route('admin.subscription');
    }

    public function render()
    {
        return view('livewire.admin.subscription');
    }
}
