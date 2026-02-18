<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Front\Home;
use App\Livewire\Front\ProgramIndex;
use App\Livewire\Front\ProgramDetail;
use App\Livewire\Front\ProgramCheckout;
use App\Livewire\Front\QurbanIndex;
use App\Livewire\Front\QurbanCheckout;
use App\Livewire\Front\QurbanTabungan;
use App\Livewire\Front\QurbanTabunganCheckout;
use App\Livewire\Front\QurbanHistory;
use App\Livewire\Front\QurbanTransactionDetail;
use App\Livewire\Front\QurbanSavingsDetail;
use App\Livewire\Front\MyDonation;
use App\Livewire\Front\PaymentMethod;
use App\Livewire\Front\Profile;
use App\Livewire\Front\ProfileEdit;
use App\Livewire\Front\ChangePassword;
use App\Livewire\Front\FoundationProfile;
use App\Livewire\Front\FoundationLegality;
use App\Livewire\Front\Report;
use App\Livewire\Front\SearchPage;
use App\Http\Controllers\XenditWebhookController;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use Illuminate\Support\Facades\Auth;

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

// Auth Routes (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.forgot');
});

// Logout Route
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');


Route::get('/', Home::class)->name('home');
Route::get('/search', SearchPage::class)->name('search.index');
Route::get('/program', ProgramIndex::class)->name('program.index');
Route::get('/program/{slug}', ProgramDetail::class)->name('program.detail');
Route::get('/program/{slug}/checkout', ProgramCheckout::class)->name('program.checkout');

// Qurban Routes
Route::get('/qurban', QurbanIndex::class)->name('qurban.index');
Route::get('/qurban/tabungan', QurbanTabungan::class)->name('qurban.tabungan');

Route::get('/payment', PaymentMethod::class)->name('payment.method');
Route::get('/payment/status', \App\Livewire\Front\PaymentStatus::class)->name('payment.status');
Route::get('/transaction/{id}/status', \App\Livewire\Front\TransactionStatus::class)->name('transaction.status');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/qurban/checkout', QurbanCheckout::class)->name('qurban.checkout');
    Route::get('/qurban/tabungan/checkout', QurbanTabunganCheckout::class)->name('qurban.tabungan.checkout');
    Route::get('/qurban/history', QurbanHistory::class)->name('qurban.history');
    Route::get('/qurban/history/{id}', QurbanTransactionDetail::class)->name('qurban.transaction.detail');
    Route::get('/qurban/savings/{id}', QurbanSavingsDetail::class)->name('qurban.savings.detail');

    Route::get('/my-donation', MyDonation::class)->name('my-donation.index');
    
    Route::get('/profile', Profile::class)->name('profile.index');
    Route::get('/profile/edit', ProfileEdit::class)->name('profile.edit');
    Route::get('/profile/change-password', ChangePassword::class)->name('profile.change-password');
    Route::get('/foundation/profile', FoundationProfile::class)->name('foundation.profile');
    Route::get('/foundation/legality', FoundationLegality::class)->name('foundation.legality');
    Route::get('/report', Report::class)->name('report.index');
});

// Webhook Route
Route::post('/webhooks/xendit/invoice', [XenditWebhookController::class, 'handleInvoice'])->name('webhooks.xendit.invoice');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/programs', \App\Livewire\Admin\Program::class)->name('programs');
    Route::get('/programs/{id}/manage', \App\Livewire\Admin\ProgramManage::class)->name('programs.manage');
    Route::get('/categories', \App\Livewire\Admin\Category::class)->name('categories');
    Route::get('/akad-types', \App\Livewire\Admin\AkadType::class)->name('akad-types');
    Route::get('/banners', \App\Livewire\Admin\Banner::class)->name('banners');
    Route::get('/donations', \App\Livewire\Admin\DonationList::class)->name('donations');
    Route::get('/qurban', \App\Livewire\Admin\Qurban::class)->name('qurban');
    Route::get('/users', \App\Livewire\Admin\UserList::class)->name('users');
    Route::get('/profile', \App\Livewire\Admin\Profile::class)->name('profile');
    Route::get('/settings', \App\Livewire\Admin\Settings::class)->name('settings');
    Route::get('/whatsapp', \App\Livewire\Admin\WhatsappSetting::class)->name('whatsapp');
    Route::get('/meta-setting', \App\Livewire\Admin\MetaSetting::class)->name('meta-setting');
    Route::get('/maintenance-fee', \App\Livewire\Admin\MaintenanceFee::class)->name('maintenance-fee');
    Route::get('/bank-followup', \App\Livewire\Admin\BankFollowup::class)->name('bank-followup');

    // Quill Editor Image Upload
    Route::post('/upload-editor-image', [\App\Http\Controllers\EditorImageUploadController::class, 'store'])->name('upload-editor-image');
});

Route::get('/login-required', \App\Livewire\Front\LoginRequired::class)->name('login.required');
