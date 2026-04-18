<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuService
{
    protected $merchantCode;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->merchantCode = config('duitku.merchant_code');
        $this->apiKey = config('duitku.api_key');
        $this->baseUrl = config('duitku.sandbox') 
            ? 'https://api-sandbox.duitku.com/api/merchant' 
            : 'https://api-prod.duitku.com/api/merchant';
    }

    /**
     * Create an invoice/transaction on Duitku
     */
    public function createInvoice($params)
    {
        $timestamp = round(microtime(true) * 1000);
        
        $signature = hash('sha256', $this->merchantCode . $timestamp . $this->apiKey);

        $response = Http::withHeaders([
            'x-duitku-signature' => $signature,
            'x-duitku-timestamp' => $timestamp,
            'x-duitku-merchantcode' => $this->merchantCode,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/createInvoice', array_merge([
            'paymentAmount' => 0,
            'merchantOrderId' => '',
            'productDetails' => '',
            'email' => '',
            'callbackUrl' => config('duitku.callback_url'),
            'returnUrl' => config('duitku.return_url'),
            'expiryPeriod' => 60, // 60 minutes
        ], $params));

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Duitku Create Invoice Error: ' . $response->body());
        return [
            'statusCode' => '99',
            'statusMessage' => 'Internal Connection Error: ' . $response->status()
        ];
    }

    /**
     * Validate callback signature
     */
    public function validateCallback($data)
    {
        $merchantCode = $data['merchantCode'] ?? '';
        $amount = $data['amount'] ?? '';
        $merchantOrderId = $data['merchantOrderId'] ?? '';
        $signature = $data['signature'] ?? '';

        $calcSignature = md5($merchantCode . $amount . $merchantOrderId . $this->apiKey);

        return $signature === $calcSignature;
    }
}
