<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaConversionService
{
    protected $apiVersion = 'v21.0';

    /**
     * Send InitiateCheckout event to Meta CAPI
     */
    public function sendInitiateCheckout(Payment $payment)
    {
        if (!AppSetting::get('meta_capi_enabled')) {
            return;
        }

        $eventId = 'IC-' . $payment->external_id;

        $this->sendEvent('InitiateCheckout', $payment, $eventId);
    }

    /**
     * Send Purchase event to Meta CAPI
     */
    public function sendPurchase(Payment $payment)
    {
        if (!AppSetting::get('meta_capi_enabled')) {
            return;
        }

        // Use same event_id pattern if purchase happens immediately, or unique if separate
        // For purchase, usually external_id is good enough as unique key
        $eventId = 'PUR-' . $payment->external_id;

        $this->sendEvent('Purchase', $payment, $eventId);
    }

    /**
     * Generic send event method
     */
    protected function sendEvent(string $eventName, Payment $payment, string $eventId)
    {
        $pixelId = AppSetting::get('meta_pixel_id');
        $accessToken = AppSetting::get('meta_access_token');
        $testEventCode = AppSetting::get('meta_test_event_code');

        if (!$pixelId || !$accessToken) {
            Log::warning('Meta CAPI: Missing Pixel ID or Access Token');
            return;
        }

        $url = "https://graph.facebook.com/{$this->apiVersion}/{$pixelId}/events";

        $userData = [
            'client_ip_address' => request()->ip(),
            'client_user_agent' => request()->userAgent(),
        ];

        // Hash user data if available
        if ($payment->customer_email) {
            $userData['em'] = hash('sha256', strtolower(trim($payment->customer_email)));
        }
        
        if ($payment->customer_phone) {
            // Normalize phone: remove non-numeric
            $phone = preg_replace('/[^0-9]/', '', $payment->customer_phone);
            // Simple normalization for Indonesia
            if (str_starts_with($phone, '0')) {
                $phone = '62' . substr($phone, 1);
            } elseif (str_starts_with($phone, '8')) {
                $phone = '62' . $phone;
            }
            $userData['ph'] = hash('sha256', $phone);
        }
        
        // External ID for deduplication if user is logged in
        if ($payment->user_id) {
            $userData['external_id'] = hash('sha256', (string)$payment->user_id);
        }

        $customData = [
            'value' => (float) $payment->total,
            'currency' => 'IDR',
            'content_name' => $this->getContentName($payment),
        ];

        $eventData = [
            'event_name' => $eventName,
            'event_time' => now()->timestamp,
            'event_id' => $eventId,
            'event_source_url' => url()->current(),
            'action_source' => 'website',
            'user_data' => $userData,
            'custom_data' => $customData,
        ];

        $payload = [
            'data' => [$eventData],
            'access_token' => $accessToken,
        ];

        if ($testEventCode) {
            $payload['test_event_code'] = $testEventCode;
        }

        try {
            $response = Http::post($url, $payload);

            if ($response->successful()) {
                Log::info("Meta CAPI Success: {$eventName} for {$eventId}");
            } else {
                Log::error("Meta CAPI Failed: {$eventName} - " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("Meta CAPI Exception: " . $e->getMessage());
        }
    }

    protected function getContentName(Payment $payment)
    {
        if ($payment->transaction_type === 'program' && $payment->program) {
            return 'Donasi: ' . $payment->program->title;
        } elseif ($payment->transaction_type === 'qurban_langsung' && $payment->qurbanOrder) {
            return 'Qurban: ' . ($payment->qurbanOrder->animal->name ?? 'Hewan Qurban');
        } elseif ($payment->transaction_type === 'qurban_tabungan') {
            return 'Tabungan Qurban';
        }
        return 'Donasi Umum';
    }
}
