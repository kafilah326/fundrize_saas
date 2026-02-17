<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'month',
        'total_amount',
        'fee_amount',
        'status',
        'proof_of_payment',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];
}
