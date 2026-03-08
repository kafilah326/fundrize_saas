<?php

namespace App\Livewire\Admin;

use App\Models\AdminNotification;
use App\Models\Payment;
use App\Models\ZakatTransaction;
use App\Services\WhatsAppNotificationService;
use App\Services\MetaConversionService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class ZakatList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $statusFilter = '';
    public $zakatTypeFilter = '';
    public $dateFrom;
    public $dateTo;

    // Detail Modal
    public $isOpen = false;
    public $selectedPayment = null;

    protected $queryString = [
        'search'          => ['except' => ''],
        'statusFilter'    => ['except' => ''],
        'zakatTypeFilter' => ['except' => ''],
        'dateFrom'        => ['except' => ''],
        'dateTo'          => ['except' => ''],
    ];

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'zakatTypeFilter', 'dateFrom', 'dateTo']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function showDetail($id)
    {
        $this->selectedPayment = Payment::with(['user', 'zakatTransaction'])->find($id);
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->selectedPayment = null;
    }

    public function confirmPayment($id)
    {
        $payment = Payment::find($id);

        if (! $payment) {
            session()->flash('error', 'Data pembayaran tidak ditemukan.');
            return;
        }

        if ($payment->payment_type !== 'bank_transfer') {
            session()->flash('error', 'Hanya pembayaran transfer bank yang dapat dikonfirmasi manual.');
            return;
        }

        if ($payment->status !== 'pending') {
            session()->flash('error', 'Hanya pembayaran dengan status pending yang dapat dikonfirmasi.');
            return;
        }

        // 1. Update Payment status
        $payment->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        // 2. Update ZakatTransaction
        if ($payment->transaction_type === 'zakat') {
            $zakatTrx = ZakatTransaction::where('transaction_id', $payment->external_id)->first();
            if ($zakatTrx) {
                $zakatTrx->update(['status' => 'success']);
            }
        }

        // 3. Notify admin
        AdminNotification::notify(
            'payment_success',
            'Pembayaran Zakat Dikonfirmasi!',
            'Zakat ' . $payment->external_id . ' sebesar Rp ' . number_format($payment->total) . ' dari ' . ($payment->customer_name ?? 'Hamba Allah') . ' — dikonfirmasi manual',
            ['payment_id' => $payment->id, 'amount' => $payment->total]
        );

        // 4. Send Meta Purchase event
        try {
            $metaService = app(MetaConversionService::class);
            $metaService->sendPurchase($payment);
        } catch (\Exception $e) {
            Log::error('Meta CAPI Purchase Error (Zakat): ' . $e->getMessage());
        }

        // 5. Send WhatsApp notification
        try {
            app(WhatsAppNotificationService::class)->notifyPaymentSuccess($payment);
        } catch (\Exception $e) {
            Log::error('WhatsApp Notification Error (Zakat): ' . $e->getMessage());
        }

        session()->flash('success', 'Pembayaran zakat berhasil dikonfirmasi.');
        $this->closeModal();
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        $statQuery = Payment::where('transaction_type', 'zakat')->where('status', 'paid');

        $statToday     = (clone $statQuery)->whereDate('paid_at', now()->toDateString())->sum(\DB::raw('amount + COALESCE(unique_code, 0)'));
        $statThisMonth = (clone $statQuery)->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum(\DB::raw('amount + COALESCE(unique_code, 0)'));
        $statTotal     = (clone $statQuery)->sum(\DB::raw('amount + COALESCE(unique_code, 0)'));

        $payments = Payment::with(['user', 'zakatTransaction'])
            ->where('transaction_type', 'zakat')
            ->where(function ($query) {
                $query->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_email', 'like', '%' . $this->search . '%')
                    ->orWhere('external_id', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->zakatTypeFilter, function ($query) {
                $query->whereHas('zakatTransaction', function ($q) {
                    $q->where('zakat_type', $this->zakatTypeFilter);
                });
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.zakat-list', [
            'payments'      => $payments,
            'statToday'     => $statToday,
            'statThisMonth' => $statThisMonth,
            'statTotal'     => $statTotal,
        ]);
    }
}
