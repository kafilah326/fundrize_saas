<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\WhatsappMessageLog;
use App\Services\Contracts\WhatsAppProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService implements WhatsAppProviderInterface
{
    /**
     * Get the Fonnte API Token from settings.
     */
    private function getToken(): ?string
    {
        return AppSetting::get('fonnte_token');
    }

    /**
     * Check if Fonnte is selected and enabled.
     */
    public function isEnabled(): bool
    {
        $enabled = (bool) AppSetting::get('starsender_enabled'); // Using the same toggle for general WA notification
        $provider = AppSetting::get('wa_provider', 'starsender');
        
        return $enabled && $provider === 'fonnte' && !empty($this->getToken());
    }

    /**
     * Normalize the phone number for Fonnte (country code 62).
     */
    public function normalizePhone(?string $number): ?string
    {
        if (empty($number)) {
            return null;
        }

        // Remove all non-numeric characters
        $number = preg_replace('/[^0-9]/', '', $number);

        // Replace leading 0 with 62
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }

        // Add 62 if missing (assuming Indonesia)
        if (substr($number, 0, 2) !== '62') {
            $number = '62' . $number;
        }

        return $number;
    }

    /**
     * Send a text message using Fonnte API.
     */
    public function sendMessage(string $to, string $body, string $eventType = 'manual', ?int $paymentId = null): array
    {
        $normalizedTo = $this->normalizePhone($to);

        $log = new WhatsappMessageLog;
        $log->phone = $normalizedTo ?? $to;
        $log->message = $body;
        $log->event_type = $eventType;
        $log->payment_id = $paymentId;

        $hasToken = !empty($this->getToken());
        
        if (!$this->isEnabled() && !($eventType === 'test' && $hasToken)) {
            $log->status = 'failed';
            $log->response_data = ['error' => 'WhatsApp disabled, provider mismatch, or Token missing'];
            $log->save();

            return ['status' => false, 'message' => 'Disabled or missing Token'];
        }

        if (!$normalizedTo) {
            $log->status = 'failed';
            $log->response_data = ['error' => 'Invalid phone number'];
            $log->save();

            return ['status' => false, 'message' => 'Invalid number'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->getToken(),
                // 'Content-Type' => 'application/json',
            ])->post('https://api.fonnte.com/send', [
                'target' => $normalizedTo,
                'message' => $body,
                'countryCode' => '62'
            ]);

            $responseData = $response->json();
            $log->response_data = $responseData;

            // Fonnte success returns boolean true on 'status' key
            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === true) {
                $log->status = 'sent';
                $log->save();

                return ['status' => true, 'data' => $responseData];
            }

            Log::warning('Fonnte send failed: ' . $response->body());
            $log->status = 'failed';
            $log->save();

            return ['status' => false, 'message' => $responseData['reason'] ?? $response->body()];

        } catch (\Exception $e) {
            Log::error('Fonnte send error: ' . $e->getMessage());
            $log->status = 'failed';
            $log->response_data = ['exception' => $e->getMessage()];
            $log->save();

            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}
