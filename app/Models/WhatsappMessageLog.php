<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class WhatsappMessageLog extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
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
