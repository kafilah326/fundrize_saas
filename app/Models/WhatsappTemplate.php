<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    protected $fillable = [
        'name',
        'type',
        'event',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get a random active template for a given type and event.
     * Returns null if no template found.
     */
    public static function getRandomTemplate(string $type, string $event): ?self
    {
        return static::where('type', $type)
            ->where('event', $event)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();
    }

    /**
     * Get all active templates for a given type and event.
     */
    public static function getActiveTemplates(string $type, string $event)
    {
        return static::where('type', $type)
            ->where('event', $event)
            ->where('is_active', true)
            ->get();
    }

    /**
     * Get the type label.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'donasi' => 'Donasi Program',
            'qurban' => 'Qurban',
            'tabungan_qurban' => 'Tabungan Qurban',
            'zakat' => 'Zakat',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get the event label.
     */
    public function getEventLabelAttribute(): string
    {
        return match ($this->event) {
            'payment_created' => 'Pembayaran Dibuat',
            'payment_success' => 'Pembayaran Berhasil',
            'payment_expired' => 'Pembayaran Expired',
            default => ucfirst(str_replace('_', ' ', $this->event)),
        };
    }
}
