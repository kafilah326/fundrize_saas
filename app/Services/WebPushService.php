<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\AppSetting;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Illuminate\Support\Facades\Log;

class WebPushService
{
    public function sendToAll(string $title, string $message, string $type, ?string $url = null): void
    {
        $subscriptions = PushSubscription::all();
        
        if ($subscriptions->isEmpty()) {
            return;
        }

        $vapidPublicKey = AppSetting::get('vapid_public_key');
        $vapidPrivateKey = AppSetting::get('vapid_private_key');

        if (!$vapidPublicKey || !$vapidPrivateKey) {
            Log::warning('VAPID keys not configured. Cannot send web push.');
            return;
        }

        $auth = [
            'VAPID' => [
                'subject' => config('app.url'),
                'publicKey' => $vapidPublicKey,
                'privateKey' => $vapidPrivateKey,
            ],
        ];

        try {
            $webPush = new WebPush($auth);
            
            $payload = json_encode([
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'data' => [
                    'url' => $url ?? '/admin/dashboard'
                ]
            ]);

            foreach ($subscriptions as $sub) {
                // Check if keys are robust enough
                if (!$sub->endpoint) continue;

                $webPush->queueNotification(
                    Subscription::create([
                        'endpoint' => $sub->endpoint,
                        'publicKey' => $sub->p256dh_key,
                        'authToken' => $sub->auth_token,
                        'contentEncoding' => $sub->content_encoding,
                    ]),
                    $payload
                );
            }

            foreach ($webPush->flush() as $report) {
                $endpoint = $report->getRequest()->getUri()->__toString();

                if ($report->isSuccess()) {
                    Log::info("[WebPush] Message sent successfully for subscription {$endpoint}.");
                } else {
                    Log::warning("[WebPush] Message failed to sent for subscription {$endpoint}: {$report->getReason()}");
                    
                    // If subscription is expired/invalid, delete it
                    if ($report->isSubscriptionExpired()) {
                        PushSubscription::where('endpoint', $endpoint)->delete();
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Web Push Error: ' . $e->getMessage());
        }
    }
}
