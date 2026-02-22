<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected $secretKey;

    protected $baseUrl = 'https://api.xendit.co';

    protected $mode;

    public function __construct()
    {
        $this->secretKey = AppSetting::get('xendit_secret_key');
        $this->mode = AppSetting::get('xendit_mode', 'test');
    }

    /**
     * Check if Xendit credentials are configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->secretKey);
    }

    /**
     * Get current Xendit mode (test/live).
     */
    public function getMode(): string
    {
        return $this->mode;
    }

    public function createInvoice($data)
    {
        // Add default parameters
        $payload = array_merge([
            'currency' => 'IDR',
            'invoice_duration' => 86400, // 24 hours
        ], $data);

        Log::info("Xendit [{$this->mode}] Creating invoice: {$data['external_id']}");

        $response = Http::withBasicAuth($this->secretKey, '')
            ->post("{$this->baseUrl}/v2/invoices", $payload);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Xendit Error: '.$response->body());
    }

    public function getInvoice($invoiceId)
    {
        $response = Http::withBasicAuth($this->secretKey, '')
            ->get("{$this->baseUrl}/v2/invoices/{$invoiceId}");

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Xendit Error: '.$response->body());
    }
}
