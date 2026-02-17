<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QurbanOrder extends Model
{
    protected $fillable = [
        'transaction_id',
        'user_id',
        'qurban_animal_id',
        'hijri_year',
        'donor_name',
        'whatsapp',
        'email',
        'qurban_name',
        'address',
        'city',
        'postal_code',
        'slaughter_method',
        'delivery_method',
        'amount',
        'payment_method',
        'status',
        'created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(QurbanAnimal::class, 'qurban_animal_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'external_id', 'transaction_id');
    }

    public function documentations()
    {
        return $this->morphMany(QurbanDocumentation::class, 'documentable');
    }
}
