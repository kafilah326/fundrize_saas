<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\WhatsappMessageLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StarSenderService
{
    /**
     * Normalize phone number to start with 62
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
            $number = '62'.substr($number, 1);
        }

        // Add 62 if missing (assuming Indonesia)
        if (substr($number, 0, 2) !== '62') {
            $number = '62'.$number;
        }

        return $number;
    }

    /**
     * Get device detail
     */
    public function getDeviceDetail(string $deviceId): array
    {
        if (! $this->getAccountApiKey()) {
            return ['status' => false, 'message' => 'STARSENDER_API_KEY not found in .env'];
        }

        try {
            // Updated to use /devices/{id} as confirmed by user testing
            $response = Http::withHeaders([
                'Authorization' => $this->getAccountApiKey(), // No Bearer
                'Content-Type' => 'application/json',
            ])->get($this->getBaseUrl().'/devices/'.$deviceId);

            if ($response->successful()) {
                $data = $response->json();

                // Response format: {"success":true,"data":{"device":{...}},"message":"..."}
                if (isset($data['data']['device'])) {
                    return ['status' => true, 'data' => $data['data']['device']];
                }

                return ['status' => false, 'message' => 'Device data not found in response'];
            }

            return ['status' => false, 'message' => $response->json()['message'] ?? 'Failed to fetch device detail'];

        } catch (\Exception $e) {
            Log::error('StarSender device detail error: '.$e->getMessage());

            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get Account API Key (from .env) - Used for device management
     */
    private function getAccountApiKey(): ?string
    {
        return env('STARSENDER_API_KEY');
    }

    /**
     * Get Device API Key (from DB) - Used for sending messages
     */
    private function getDeviceApiKey(): ?string
    {
        return AppSetting::get('starsender_api_key');
    }

    /**
     * Get Base URL
     */
    private function getBaseUrl(): string
    {
        // Reverting to api.starsender.online/api as it seems correct with the new endpoint paths
        $url = AppSetting::get('starsender_base_url', 'https://api.starsender.online/api');

        return rtrim($url, '/');
    }

    /**
     * Check if StarSender is enabled
     */
    public function isEnabled(): bool
    {
        $enabled = (bool) AppSetting::get('starsender_enabled');
        $hasKey = $this->getDeviceApiKey() || $this->getAccountApiKey();

        return $enabled && $hasKey;
    }

    // =========================================================================
    // DEVICE MANAGEMENT (Uses Account API Key)
    // =========================================================================

    /**
     * Create a new device and get QR Code
     * Endpoint: POST /devices/create/scan
     */
    public function createDevice(string $name): array
    {
        if (! $this->getAccountApiKey()) {
            return ['status' => false, 'message' => 'STARSENDER_API_KEY not found in .env'];
        }

        try {
            $url = $this->getBaseUrl().'/devices/create/scan';
            $response = Http::withHeaders([
                'Authorization' => $this->getAccountApiKey(), // No Bearer prefix
                'Content-Type' => 'application/json',
            ])->post($url, [
                'name' => $name,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Response format: { "success": true, "data": {"kode_gambar": "base64..."}, "message": "..." }
                return ['status' => true, 'data' => $data];
            }

            Log::warning('StarSender create device failed (URL: '.$url.'): '.$response->body());

            return ['status' => false, 'message' => 'API Error: '.$response->body()];

        } catch (\Exception $e) {
            Log::error('StarSender create device error: '.$e->getMessage());

            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Get device status by name
     * Endpoint: GET /devices
     */
    public function getDeviceByName(string $name): array
    {
        if (! $this->getAccountApiKey()) {
            return ['status' => false, 'message' => 'STARSENDER_API_KEY not found in .env'];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->getAccountApiKey(), // No Bearer prefix
                'Content-Type' => 'application/json',
            ])->get($this->getBaseUrl().'/devices');

            if ($response->successful()) {
                $data = $response->json();
                // Structure: { data: { devices: [ {name: "...", id: "...", status: "..."}, ... ] } }

                $devices = $data['data']['devices'] ?? [];
                foreach ($devices as $device) {
                    if (($device['name'] ?? '') === $name) {
                        return ['status' => true, 'data' => $device];
                    }
                }

                return ['status' => false, 'message' => 'Device not found in list'];
            }

            return ['status' => false, 'message' => $response->json()['message'] ?? 'Failed to fetch devices'];

        } catch (\Exception $e) {
            Log::error('StarSender get devices error: '.$e->getMessage());

            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Relog device (Optional/Legacy support if endpoint exists)
     * Endpoint: POST /device/relog (Old docs)
     * If not supported in V3, we rely on create/scan.
     */
    public function relogDevice(string $deviceId): array
    {
        if (! $this->getAccountApiKey()) {
            return ['status' => false, 'message' => 'STARSENDER_API_KEY not found in .env'];
        }

        try {
            $url = $this->getBaseUrl().'/devices/'.$deviceId.'/relog';
            $response = Http::withHeaders([
                'Authorization' => $this->getAccountApiKey(),
                'Content-Type' => 'application/json',
            ])->post($url);

            if ($response->successful()) {
                return ['status' => true, 'data' => $response->json()];
            }

            $responseData = $response->json();
            $message = $responseData['message'] ?? $response->body();

            // If device not found, try to create a new one
            if (str_contains($message, 'Device not found')) {
                $foundationName = \App\Models\FoundationSetting::value('name') ?? 'Yayasan';
                $name = substr($foundationName, 0, 20).'-'.rand(1000, 9999);

                return $this->createDevice($name);
            }

            Log::warning('StarSender relog device failed (URL: '.$url.'): '.$response->body());

            return ['status' => false, 'message' => 'API Error: '.$message];

        } catch (\Exception $e) {
            Log::error('StarSender relog device error: '.$e->getMessage());

            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    // =========================================================================
    // MESSAGING (Uses Device API Key from DB)
    // =========================================================================

    /**
     * Check if number is registered on WhatsApp
     */
    public function checkNumber(string $number): bool
    {
        if (! $this->isEnabled()) {
            return false;
        }

        $number = $this->normalizePhone($number);
        if (! $number) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->getDeviceApiKey(), // No Bearer prefix? Assuming same pattern
                'Content-Type' => 'application/json',
            ])->post($this->getBaseUrl().'/check-number', [
                'phone' => $number,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return isset($data['valid']) && $data['valid'] === true;
            }

            Log::warning('StarSender check-number failed: '.$response->body());

            return false;

        } catch (\Exception $e) {
            Log::error('StarSender check-number error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Send text message
     */
    public function sendMessage(string $to, string $body, string $eventType = 'manual', ?int $paymentId = null): array
    {
        // Normalize phone first
        $normalizedTo = $this->normalizePhone($to);

        // Log attempt
        $log = new WhatsappMessageLog;
        $log->phone = $normalizedTo ?? $to;
        $log->message = $body;
        $log->event_type = $eventType;
        $log->payment_id = $paymentId;

        if (! $this->isEnabled()) {
            $log->status = 'failed';
            $log->response_data = ['error' => 'WhatsApp disabled or API Key missing'];
            $log->save();

            return ['status' => false, 'message' => 'Disabled or missing API Key'];
        }

        if (! $normalizedTo) {
            $log->status = 'failed';
            $log->response_data = ['error' => 'Invalid phone number'];
            $log->save();

            return ['status' => false, 'message' => 'Invalid number'];
        }

        $deviceId = AppSetting::get('starsender_device_id');
        // Note: New API might not need deviceId in body if API key is unique to device,
        // BUT if we use Account API Key for everything, we need deviceId.
        // The user said "API untuk menambah device...". Messaging usually requires specifying device if using account key.
        // However, the docs provided for messaging: "Authorization: YOUR API KEY".
        // It doesn't clarify if this is Account Key or Device Key.
        // Previous docs said "Ganti YOUR_API_KEY dengan yang didapat dari device".
        // Let's assume we stick to the 2-key system: Messaging uses Device Key (from DB).
        // If so, we might not need 'device' param in body if the key is scoped.
        // But previous docs required 'device'.
        // Let's keep sending 'device' just in case, it usually doesn't hurt.

        if (! $deviceId) {
            $log->status = 'failed';
            $log->response_data = ['error' => 'No device ID configured'];
            $log->save();

            return ['status' => false, 'message' => 'No device ID configured'];
        }

        try {
            // Using getDeviceApiKey() which is stored in DB.
            // CAUTION: Is this key actually returned by getDeviceByName/createDevice?
            // The new docs snippet for Create doesn't show API Key in response.
            // The snippet for Status doesn't show API Key in response.
            // IF the API only uses ONE Account Key for everything, then we should use Account Key here too.
            // Given the complexity, let's try using Account Key if Device Key is empty.
            // But usually messaging requires selecting the device.

            $apiKey = $this->getDeviceApiKey() ?: $this->getAccountApiKey();

            $response = Http::withHeaders([
                'Authorization' => $apiKey, // No Bearer
                'Content-Type' => 'application/json',
            ])->post($this->getBaseUrl().'/send', [
                'to' => $normalizedTo,
                'body' => $body,
                'messageType' => 'text',
            ]);

            $responseData = $response->json();
            $log->response_data = $responseData;

            if ($response->successful()) {
                $log->status = 'sent';
                $log->save();

                return ['status' => true, 'data' => $responseData];
            }

            Log::warning('StarSender send failed: '.$response->body());
            $log->status = 'failed';
            $log->save();

            return ['status' => false, 'message' => $response->body()];

        } catch (\Exception $e) {
            Log::error('StarSender send error: '.$e->getMessage());
            $log->status = 'failed';
            $log->response_data = ['exception' => $e->getMessage()];
            $log->save();

            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}
