<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use BelongsToTenant;

    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'external_id',
        'transaction_type', // program, qurban_langsung, qurban_tabungan, zakat
        'user_id',
        'program_id',
        'qurban_order_id',
        'qurban_saving_id',
        'zakat_transaction_id',
        'unique_code',
        'customer_name',
        'customer_email',
        'customer_phone',
        'payment_type',
        'amount',
        'admin_fee',
        'total',
        'payment_method',
        'status',
        'xendit_invoice_id',
        'xendit_invoice_url',
        'checkout_data',
        'paid_at',
        'expired_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    
    public function qurbanOrder()
    {
        return $this->belongsTo(QurbanOrder::class);
    }
    
    public function qurbanSaving()
    {
        return $this->belongsTo(QurbanSaving::class);
    }

    public function zakatTransaction()
    {
        return $this->belongsTo(ZakatTransaction::class);
    }

    public function whatsappMessageLogs()
    {
        return $this->hasMany(WhatsappMessageLog::class);
    }

    public function donation()
    {
        return $this->hasOne(Donation::class, 'transaction_id', 'external_id');
    }

    protected $casts = [
        'checkout_data' => 'array',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'amount' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];
}
