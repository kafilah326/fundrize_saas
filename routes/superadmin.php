<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\SuperAdmin\Login;
use App\Livewire\SuperAdmin\Dashboard;
use App\Livewire\SuperAdmin\TenantList;
use App\Livewire\SuperAdmin\TenantCreate;
use App\Livewire\SuperAdmin\TenantDetail;
use App\Livewire\SuperAdmin\PlanManager;
use App\Livewire\SuperAdmin\TransactionList;

// ============================================================
// SUPERADMIN DOMAIN ROUTES
// Domain: superadmin.fundrize.test (atau sesuai config)
// ============================================================

// Root redirect — kalau belum login, langsung ke /login
Route::get('/', function () {
    if (auth('superadmin')->check()) {
        return redirect()->route('superadmin.dashboard');
    }
    return redirect()->route('superadmin.login');
});

// Guest-only: halaman login
Route::middleware('guest:superadmin')->group(function () {
    Route::get('/login', Login::class)->name('superadmin.login');
});

// Logout
Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::guard('superadmin')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('superadmin.login');
})->name('superadmin.logout');

// Protected: semua route yang butuh login superadmin
Route::middleware('auth:superadmin')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('superadmin.dashboard');
    Route::get('/tenants', TenantList::class)->name('superadmin.tenants');
    Route::get('/tenants/create', TenantCreate::class)->name('superadmin.tenants.create');
    Route::get('/tenants/{id}', TenantDetail::class)->name('superadmin.tenants.detail');
    Route::get('/plans', PlanManager::class)->name('superadmin.plans');
    Route::get('/addons', \App\Livewire\SuperAdmin\AddonManager::class)->name('superadmin.addons');
    Route::get('/transactions', TransactionList::class)->name('superadmin.transactions');
    // Route::get('/domains', DomainManager::class)->name('superadmin.domains');
});
