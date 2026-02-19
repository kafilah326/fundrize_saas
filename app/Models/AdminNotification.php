<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'is_read',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
    ];

    /**
     * Create a new admin notification.
     *
     * @param  string  $type  'new_transaction' or 'payment_success'
     */
    public static function notify(string $type, string $title, string $message, ?array $data = null): self
    {
        $notification = static::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        // Send Web Push Notification
        try {
            app(\App\Services\WebPushService::class)->sendToAll(
                $title,
                $message,
                $type
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Web Push Error: ' . $e->getMessage());
        }

        return $notification;
    }
}
