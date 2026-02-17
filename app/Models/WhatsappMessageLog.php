<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMessageLog extends Model
{
    protected $fillable = [
        'phone',
        'message',
        'event_type',
        'status',
        'payment_id',
        'response_data',
    ];

    protected $casts = [
        'response_data' => 'array',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
