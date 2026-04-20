<?php

namespace App\Livewire\SaaS;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Services\TenantProvisioningService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.saas')]
#[Title('Registrasi Yayasan')]
class Registration extends Component
{
    public $step = 1; // 1: Plan, 2: Admin Info, 3: Foundation Info, 4: Billing

    // Step 1: Plan
    public $selectedPlanSlug;
    public $selectedPlan;

    // Step 2: Admin Info
    public $adminName;
    public $adminEmail;
    public $adminPassword;

    // Step 3: Foundation Info
    public $foundationName;
    public $foundationSlug;
    public $foundationPhone;

    public function mount()
    {
        $this->selectedPlanSlug = request()->query('plan', 'trial');
        $this->updatedSelectedPlanSlug($this->selectedPlanSlug);
    }

    public function updatedSelectedPlanSlug($value)
    {
        $this->selectedPlan = Plan::where('slug', $value)->first() ?? Plan::where('slug', 'trial')->first();
    }

    public function updatedFoundationName($value)
    {
        // Only auto-generate if the slug hasn't been manually edited/filled yet
        if (!$this->foundationSlug) {
            $this->foundationSlug = Str::slug($value);
        }
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validate(['selectedPlanSlug' => 'required|exists:plans,slug']);
            $this->step = 2;
        } elseif ($this->step === 2) {
            $this->validate([
                'adminName' => 'required|string|min:3',
                'adminEmail' => 'required|email|unique:users,email',
                'adminPassword' => 'required|min:8',
            ]);
            $this->step = 3;
        } elseif ($this->step === 3) {
            $this->validate([
                'foundationName' => 'required|string|min:3',
                'foundationSlug' => 'required|string|unique:tenants,slug|alpha_dash',
                'foundationPhone' => 'required|numeric|min:10',
            ]);
            
            // If it's a paid plan, go to billing. If trial/free, provision immediately.
            if ($this->selectedPlan->price > 0) {
                $this->step = 4;
            } else {
                $this->register();
            }
        }
    }

    public function prevStep()
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    public function register()
    {
        // Check availability one last time
        if (Tenant::where('slug', $this->foundationSlug)->exists()) {
            $this->addError('foundationSlug', 'Subdomain ini sudah digunakan.');
            return;
        }

        try {
            $service = app(TenantProvisioningService::class);
            
            // 1. Provision with 'active' for free, but 'pending' for paid
            $initialStatus = $this->selectedPlan->price > 0 ? 'inactive' : 'active';

            $tenant = $service->provision([
                'name' => $this->foundationName,
                'slug' => $this->foundationSlug,
                'email' => $this->adminEmail,
                'phone' => $this->foundationPhone,
                'password' => $this->adminPassword,
                'plan_id' => $this->selectedPlan->id,
            ]);

            // If paid plan, change to inactive initially (provisioning service might set it to trial)
            if ($this->selectedPlan->price > 0) {
                $tenant->update(['status' => 'inactive']);

                // 2. Generate Duitku Transaction
                $externalId = 'REG-' . time() . '-' . $tenant->id;
                $duitkuService = app(\App\Services\DuitkuService::class);
                
                $response = $duitkuService->createInvoice([
                    'paymentAmount' => (int) $this->selectedPlan->price,
                    'merchantOrderId' => $externalId,
                    'productDetails' => 'Registrasi Paket ' . $this->selectedPlan->name . ' - ' . $this->foundationName,
                    'customerVaName' => $this->foundationName,
                    'email' => $this->adminEmail,
                    'phoneNumber' => $this->foundationPhone,
                ]);

                if ($response['statusCode'] === '00') {
                    \App\Models\SaasTransaction::create([
                        'tenant_id' => $tenant->id,
                        'external_id' => $externalId,
                        'reference' => $response['reference'],
                        'type' => 'registration',
                        'amount' => $this->selectedPlan->price,
                        'status' => 'pending',
                        'metadata' => [
                            'plan_id' => $this->selectedPlan->id,
                            'plan_name' => $this->selectedPlan->name,
                        ],
                    ]);

                    // Flash session data for use in RegistrationSuccess (polling/redirection)
                    session()->flash('success_message', "Selamat! Yayasan {$this->foundationName} berhasil didaftarkan.");
                    session()->flash('tenant_id', $tenant->id);
                    session()->flash('tenant_slug', $tenant->slug);
                    session()->flash('tenant_domain', $this->foundationSlug . '.' . config('tenancy.base_domain'));
                    session()->flash('admin_email', $this->adminEmail);

                    $this->dispatch('open-duitku-pop', [
                        'reference' => $response['reference'],
                        'callbackUrl' => config('duitku.callback_url'),
                        'returnUrl' => route('central.registration_success') // Success page
                    ]);
                    
                    return;
                } else {
                    $tenant->delete(); // Rollback tenant creation if invoice fails
                    $this->addError('foundationName', 'Duitku Error: ' . $response['statusMessage']);
                    return;
                }
            }

            // Set session data for success page (for free/trial)
            session()->flash('success_message', "Selamat! Yayasan {$this->foundationName} berhasil didaftarkan.");
            session()->flash('tenant_domain', $this->foundationSlug . '.' . config('tenancy.base_domain'));
            session()->flash('admin_email', $this->adminEmail);

            return redirect()->route('central.registration_success');
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Registration Error: ' . $e->getMessage());
            $this->addError('foundationName', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $plans = Plan::active()->orderBy('sort_order')->get();
        return view('livewire.saas.registration', compact('plans'));
    }
}
