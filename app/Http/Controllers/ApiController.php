<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceFee;
use App\Models\Donation;
use App\Models\QurbanOrder;
use App\Models\QurbanSavingsDeposit;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    /**
     * Get all maintenance fees
     */
    public function getMaintenanceFees()
    {
        $fees = MaintenanceFee::orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $fees
        ]);
    }

    /**
     * Update maintenance fee status
     */
    public function updateMaintenanceFeeStatus(Request $request, $id)
    {
        $fee = MaintenanceFee::find($id);
        
        if (!$fee) {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance Fee not found'
            ], 404);
        }

        $request->validate([
            'status' => 'required|in:pending,unverified,paid'
        ]);

        $fee->status = $request->status;
        
        // Auto set paid_at if status changed to paid
        if ($request->status === 'paid' && !$fee->paid_at) {
            $fee->paid_at = now();
        }

        $fee->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $fee
        ]);
    }

    /**
     * Get all combined transactions
     */
    public function getTransactions(Request $request)
    {
        // Define limits to prevent memory exhaustion
        $limit = $request->query('limit', 500);

        // Fetch Donations
        $donations = Donation::with('program')->latest()->take($limit)->get()->map(function($d) {
            return [
                'transaction_id' => $d->transaction_id,
                'type' => 'Donasi',
                'title' => 'Donasi: ' . ($d->program->title ?? 'Program'),
                'amount' => $d->total,
                'status' => $d->status,
                'customer_name' => $d->donor_name,
                'created_at' => $d->created_at,
            ];
        });

        // Fetch Qurban Orders
        $qurbans = QurbanOrder::with('qurbanAnimal')->latest()->take($limit)->get()->map(function($q) {
            return [
                'transaction_id' => $q->transaction_id,
                'type' => 'Qurban',
                'title' => 'Qurban: ' . ($q->qurbanAnimal->name ?? 'Hewan'),
                'amount' => $q->amount,
                'status' => $q->status,
                'customer_name' => $q->donor_name,
                'created_at' => $q->created_at,
            ];
        });

        // Fetch Qurban Savings Deposits
        $savings = QurbanSavingsDeposit::with('qurbanSaving')->latest()->take($limit)->get()->map(function($s) {
            return [
                'transaction_id' => $s->transaction_id,
                'type' => 'Tabungan Qurban',
                'title' => 'Tabungan Qurban: ' . ($s->qurbanSaving->donor_name ?? 'Donatur'),
                'amount' => $s->amount,
                'status' => $s->status,
                'customer_name' => $s->qurbanSaving->donor_name ?? 'Hamba Allah',
                'created_at' => $s->created_at,
            ];
        });

        // Combine all and sort by created_at descending
        $all = collect()
                ->merge($donations)
                ->merge($qurbans)
                ->merge($savings)
                ->sortByDesc('created_at')
                ->values();

        // Simple manual pagination
        $page = (int) $request->query('page', 1);
        $perPage = (int) $request->query('per_page', 50);
        $total = $all->count();
        $paginated = $all->slice(($page - 1) * $perPage, $perPage)->values();

        return response()->json([
            'success' => true,
            'data' => $paginated,
            'meta' => [
                'total' => $total,
                'page' => $page,
                'per_page' => $perPage,
                'last_page' => ceil($total / $perPage)
            ]
        ]);
    }
}
