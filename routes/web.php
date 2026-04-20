<?php

use App\Livewire\Admin\HomepageTemplate;
use App\Http\Controllers\XenditWebhookController;
use App\Http\Controllers\PakasirWebhookController;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Front\ChangePassword;
use App\Livewire\Front\FoundationLegality;
use App\Livewire\Front\FoundationProfile;
use App\Livewire\Front\Home;
use App\Livewire\Front\MyDonation;
use App\Livewire\Front\PaymentMethod;
use App\Livewire\Front\Profile;
use App\Livewire\Front\ProfileEdit;
use App\Livewire\Front\ProgramCheckout;
use App\Livewire\Front\DynamicProgramCheckout;
use App\Livewire\Front\ProgramDetail;
use App\Livewire\Front\ProgramIndex;
use App\Livewire\Front\QurbanCheckout;
use App\Livewire\Front\QurbanHistory;
use App\Livewire\Front\QurbanIndex;
use App\Livewire\Front\QurbanSavingsDetail;
use App\Livewire\Front\QurbanTabungan;
use App\Livewire\Front\QurbanTabunganCheckout;
use App\Livewire\Front\QurbanTransactionDetail;
use App\Livewire\Front\Report;
use App\Livewire\Front\SearchPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Front\ZakatIndex;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Fix #1: If request is from superadmin domain, skip registering tenant web routes entirely
if (!app()->runningInConsole() && request()->getHost() === config('tenancy.superadmin_domain')) {
    return;
}

// SaaS Central Landing Page & Registration
Route::group(['domain' => config('tenancy.base_domain')], function () {
    Route::get('/', \App\Livewire\SaaS\LandingPage::class)->name('central.landing');
    Route::get('/register', \App\Livewire\SaaS\Registration::class)->name('central.register');
    Route::get('/registration-success', \App\Livewire\SaaS\RegistrationSuccess::class)->name('central.registration_success');

    // Duitku Callback (Central)
    Route::post('/webhooks/duitku/callback', [\App\Http\Controllers\DuitkuCallbackController::class, 'handle'])->name('webhooks.duitku.callback');
    Route::get('/webhooks/duitku/callback', function () {
        return response('Duitku Webhook Endpoint Active', 200);
    });
});

Route::middleware(['tenant.required'])->group(function () {
    // Auth Routes (Guest Only)
    Route::middleware('guest')->group(function () {
        Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
        Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
        Route::get('/forgot-password', \App\Livewire\Auth\ForgotPassword::class)->name('password.forgot');
    });

    // Logout Route
    Route::any('/logout', function () {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    })->name('logout');

    Route::get('/', \App\Livewire\Front\Home::class)->name('home');
    Route::get('/search', \App\Livewire\Front\SearchPage::class)->name('search.index');
    Route::get('/program', \App\Livewire\Front\ProgramIndex::class)->name('program.index');
    Route::get('/program/{slug}', \App\Livewire\Front\ProgramDetail::class)->name('program.detail');
    Route::get('/program/{slug}/checkout', \App\Livewire\Front\ProgramCheckout::class)->name('program.checkout');
    Route::get('/program/{slug}/checkout-dynamic', \App\Livewire\Front\DynamicProgramCheckout::class)->name('program.checkout.dynamic');
    Route::get('/foundation/profile', \App\Livewire\Front\FoundationProfile::class)->name('foundation.profile');
    Route::get('/zakat', \App\Livewire\Front\ZakatIndex::class)->name('zakat.index');

    // Qurban Routes
    Route::get('/qurban', \App\Livewire\Front\QurbanIndex::class)->name('qurban.index');
    Route::get('/qurban/checkout', \App\Livewire\Front\QurbanCheckout::class)->name('qurban.checkout');
    Route::get('/qurban/tabungan', \App\Livewire\Front\QurbanTabungan::class)->name('qurban.tabungan');

    Route::get('/report', \App\Livewire\Front\Report::class)->name('report.index');

    Route::get('/payment', \App\Livewire\Front\PaymentMethod::class)->name('payment.method');
    Route::get('/payment/status', \App\Livewire\Front\PaymentStatus::class)->name('payment.status');
    Route::get('/transaction/{id}/status', \App\Livewire\Front\TransactionStatus::class)->name('transaction.status');

    // Protected Routes
    Route::middleware('auth')->group(function () {
        Route::get('/qurban/tabungan/checkout', \App\Livewire\Front\QurbanTabunganCheckout::class)->name('qurban.tabungan.checkout');
        Route::get('/qurban/history', \App\Livewire\Front\QurbanHistory::class)->name('qurban.history');
        Route::get('/qurban/history/{id}', \App\Livewire\Front\QurbanTransactionDetail::class)->name('qurban.transaction.detail');
        Route::get('/qurban/savings/{id}', \App\Livewire\Front\QurbanSavingsDetail::class)->name('qurban.savings.detail');

        Route::get('/zakat/history', \App\Livewire\Front\ZakatHistory::class)->name('zakat.history');

        Route::get('/my-donation', \App\Livewire\Front\MyDonation::class)->name('my-donation.index');

        Route::get('/profile', \App\Livewire\Front\Profile::class)->name('profile.index');
        Route::get('/profile/edit', \App\Livewire\Front\ProfileEdit::class)->name('profile.edit');
        Route::get('/profile/change-password', \App\Livewire\Front\ChangePassword::class)->name('profile.change-password');
        Route::get('/foundation/legality', \App\Livewire\Front\FoundationLegality::class)->name('foundation.legality');
        
        // Fundraiser Routes
        Route::get('/fundraiser/register', \App\Livewire\Front\FundraiserRegister::class)->name('fundraiser.register');
        Route::get('/fundraiser/dashboard', \App\Livewire\Front\FundraiserDashboard::class)->name('fundraiser.dashboard');
        Route::get('/fundraiser/history', \App\Livewire\Front\FundraiserHistory::class)->name('fundraiser.history');
        Route::get('/fundraiser/withdrawal', \App\Livewire\Front\FundraiserWithdrawal::class)->name('fundraiser.withdrawal');
        Route::get('/fundraiser/banks', \App\Livewire\Front\FundraiserBankManage::class)->name('fundraiser.banks');
        Route::get('/fundraiser/programs', \App\Livewire\Front\FundraiserPrograms::class)->name('fundraiser.programs');
    });

    // Webhook Route (we keep it here so it gets the tenant domain bindings)
    Route::post('/webhooks/xendit/invoice', [\App\Http\Controllers\XenditWebhookController::class, 'handleInvoice'])->name('webhooks.xendit.invoice');
    Route::get('/webhooks/xendit/invoice', function () {
        return response()->json(['message' => 'Xendit Webhook Endpoint Active'], 200);
    });

    Route::post('/webhooks/pakasir/invoice', [\App\Http\Controllers\PakasirWebhookController::class, 'handleWebhook'])->name('webhooks.pakasir.invoice');
    Route::get('/webhooks/pakasir/invoice', function () {
        return response()->json(['message' => 'Pakasir Webhook Endpoint Active'], 200);
    });


    // Admin Routes
    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/programs', \App\Livewire\Admin\Program::class)->name('programs');
        Route::get('/programs/{id}/manage', \App\Livewire\Admin\ProgramManage::class)->name('programs.manage');
        Route::get('/categories', \App\Livewire\Admin\Category::class)->name('categories');
        Route::get('/akad-types', \App\Livewire\Admin\AkadType::class)->name('akad-types');
        Route::get('/banners', \App\Livewire\Admin\Banner::class)->name('banners');
        Route::get('/legal-documents', \App\Livewire\Admin\LegalDocument::class)->name('legal-documents');
        Route::get('/donations', \App\Livewire\Admin\DonationList::class)->name('donations');
        Route::get('/zakat', \App\Livewire\Admin\ZakatList::class)->name('zakat');

        // Feature-gated routes (based on tenant plan)
        Route::middleware('check.plan:qurban')->group(function () {
            Route::get('/qurban', \App\Livewire\Admin\Qurban::class)->name('qurban');
        });
        Route::middleware('check.plan:fundraiser')->group(function () {
            Route::get('/fundraisers', \App\Livewire\Admin\FundraiserList::class)->name('fundraisers');
        });
        Route::middleware('check.plan:whatsapp')->group(function () {
            Route::get('/whatsapp', \App\Livewire\Admin\WhatsappSetting::class)->name('whatsapp');
            Route::get('/whatsapp-template', \App\Livewire\Admin\WhatsappTemplate::class)->name('whatsapp-template');
        });

        Route::get('/users', \App\Livewire\Admin\UserList::class)->name('users');
        Route::get('/profile', \App\Livewire\Admin\Profile::class)->name('profile');
        Route::get('/settings', \App\Livewire\Admin\Settings::class)->name('settings');
        Route::get('/homepage-template', \App\Livewire\Admin\HomepageTemplate::class)->name('homepage-template');
        Route::get('/meta-setting', \App\Livewire\Admin\MetaSetting::class)->name('meta-setting');
        Route::get('/maintenance-fee', \App\Livewire\Admin\MaintenanceFee::class)->name('maintenance-fee');
        Route::get('/bank-followup', \App\Livewire\Admin\BankFollowup::class)->name('bank-followup');
        Route::get('/subscription', \App\Livewire\Admin\Subscription::class)->name('subscription');

        // Quill Editor Image Upload
        Route::post('/upload-editor-image', [\App\Http\Controllers\EditorImageUploadController::class, 'store'])->name('upload-editor-image');
    });

    Route::get('/login-required', \App\Livewire\Front\LoginRequired::class)->name('login.required');

    Route::middleware(['auth'])->group(function () {
        Route::post('/api/push/subscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'subscribe'])->name('push.subscribe');
        Route::post('/api/push/unsubscribe', [\App\Http\Controllers\PushSubscriptionController::class, 'unsubscribe'])->name('push.unsubscribe');
    });

    // Dynamic PWA Manifest
    Route::get('/manifest.json', [\App\Http\Controllers\ManifestController::class, 'index']);
});
