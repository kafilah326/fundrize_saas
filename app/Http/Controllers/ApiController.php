<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\MaintenanceFee;
use App\Models\QurbanOrder;
use App\Models\QurbanSavingsDeposit;
use App\Models\ZakatTransaction;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Get all maintenance fees
     */
    public function getMaintenanceFees(Request $request)
    {
        $yearParam = $request->query('year', \Carbon\Carbon::now()->year);
        $tenant = app('current_tenant');
        $feePercentage = (float) ($tenant ? $tenant->getSystemFeePercentage() : config('system.system_fee_percentage', 5));
        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = \Carbon\Carbon::now()->year;

        $limitMonth = ($yearParam == $currentYear) ? $currentMonth : 12;
        $fees = [];

        for ($m = 1; $m <= $limitMonth; $m++) {
            $monthName = \Carbon\Carbon::createFromDate($yearParam, $m, 1)->translatedFormat('F');

            $totalDonations = Donation::whereYear('created_at', $yearParam)
                ->whereMonth('created_at', $m)
                ->where('status', 'success')
                ->sum('total');

            $totalQurban = QurbanOrder::whereYear('created_at', $yearParam)
                ->whereMonth('created_at', $m)
                ->where('status', 'paid')
                ->sum('amount');

            $totalSavings = QurbanSavingsDeposit::whereYear('created_at', $yearParam)
                ->whereMonth('created_at', $m)
                ->where('status', 'paid')
                ->sum('amount');

            $totalZakat = ZakatTransaction::whereYear('created_at', $yearParam)
                ->whereMonth('created_at', $m)
                ->where('status', 'success')
                ->sum('amount');

            $totalCollected = $totalDonations + $totalQurban + $totalSavings + $totalZakat;


            if ($totalCollected == 0) {
                continue;
            }

            $feeMaintenance = ($totalCollected * $feePercentage) / 100;

            $record = MaintenanceFee::where('year', $yearParam)
                ->where('month', $m)
                ->first();

            $status = $record ? $record->status : 'not_yet';

            $fees[] = [
                'id' => $record ? $record->id : null,
                'year' => (int) $yearParam,
                'month' => $m,
                'month_name' => $monthName,
                'total_amount' => $totalCollected,
                'fee_amount' => $feeMaintenance,
                'status' => $status,
                'proof_of_payment' => $record ? $record->proof_of_payment : null,
                'paid_at' => $record ? $record->paid_at : null,
                'created_at' => $record ? $record->created_at : null,
                'updated_at' => $record ? $record->updated_at : null,
            ];
        }

        // Sort descending
        $fees = collect($fees)->sortByDesc('month')->values()->all();

        return response()->json([
            'success' => true,
            'data' => $fees,
        ]);
    }

    /**
     * Update maintenance fee status
     */
    public function updateMaintenanceFeeStatus(Request $request, $id)
    {
        $fee = MaintenanceFee::find($id);

        if (! $fee) {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance Fee not found',
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:pending,unverified,paid',
        ]);

        $fee->status = $request->status;

        // Auto set paid_at if status changed to paid
        if ($request->status === 'paid' && ! $fee->paid_at) {
            $fee->paid_at = now();
        }

        $fee->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $fee,
        ]);
    }

    /**
     * Get all combined transactions
     */
    public function getTransactions(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $tenant = app('current_tenant');
        $feePercentage = (float) ($tenant ? $tenant->getSystemFeePercentage() : config('system.system_fee_percentage', 5));

        // Define limits to prevent memory exhaustion
        $limit = $request->query('limit', 500);

        // Fetch Donations
        $donationQuery = Donation::with('program')->latest();
        if ($startDate && $endDate) {
            $donationQuery->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        } else {
            $donationQuery->take($limit);
        }
        $donations = $donationQuery->get()->map(function ($d) use ($feePercentage) {
            return [
                'transaction_id' => $d->transaction_id,
                'type' => 'Donasi',
                'title' => 'Donasi: '.($d->program->title ?? 'Program'),
                'amount' => $d->total,
                'fee_maintenance' => ($d->total * $feePercentage) / 100,
                'status' => $d->status,
                'customer_name' => $d->donor_name,
                'created_at' => $d->created_at,
            ];
        });

        // Fetch Qurban Orders
        $qurbanQuery = QurbanOrder::with('animal')->latest();
        if ($startDate && $endDate) {
            $qurbanQuery->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        } else {
            $qurbanQuery->take($limit);
        }
        $qurbans = $qurbanQuery->get()->map(function ($q) use ($feePercentage) {
            return [
                'transaction_id' => $q->transaction_id,
                'type' => 'Qurban',
                'title' => 'Qurban: '.($q->animal->name ?? 'Hewan'),
                'amount' => $q->amount,
                'fee_maintenance' => ($q->amount * $feePercentage) / 100,
                'status' => $q->status,
                'customer_name' => $q->donor_name,
                'created_at' => $q->created_at,
            ];
        });

        // Fetch Qurban Savings Deposits
        $savingsQuery = QurbanSavingsDeposit::with('qurbanSaving')->latest();
        if ($startDate && $endDate) {
            $savingsQuery->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        } else {
            $savingsQuery->take($limit);
        }
        $savings = $savingsQuery->get()->map(function ($s) use ($feePercentage) {
            return [
                'transaction_id' => $s->transaction_id,
                'type' => 'Tabungan Qurban',
                'title' => 'Tabungan Qurban: '.($s->qurbanSaving->donor_name ?? 'Donatur'),
                'amount' => $s->amount,
                'fee_maintenance' => ($s->amount * $feePercentage) / 100,
                'status' => $s->status,
                'customer_name' => $s->qurbanSaving->donor_name ?? 'Hamba Allah',
                'created_at' => $s->created_at,
            ];
        });

        // Fetch Zakat Transactions
        $zakatQuery = ZakatTransaction::latest();
        if ($startDate && $endDate) {
            $zakatQuery->whereBetween('created_at', [$startDate.' 00:00:00', $endDate.' 23:59:59']);
        } else {
            $zakatQuery->take($limit);
        }
        $zakats = $zakatQuery->get()->map(function ($z) use ($feePercentage) {
            return [
                'transaction_id' => $z->transaction_id,
                'type' => 'Zakat',
                'title' => 'Zakat: '.$z->zakat_type_label,
                'amount' => $z->amount,
                'fee_maintenance' => ($z->amount * $feePercentage) / 100,
                'status' => $z->status,
                'customer_name' => $z->donor_name,
                'created_at' => $z->created_at,
            ];
        });

        // Combine all and sort by created_at descending
        $all = collect()
            ->merge($donations)
            ->merge($qurbans)
            ->merge($savings)
            ->merge($zakats)

            ->sortByDesc('created_at')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $all,
        ]);
    }
}
