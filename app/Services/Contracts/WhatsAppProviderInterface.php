<?php

namespace App\Services\Contracts;

interface WhatsAppProviderInterface
{
    /**
     * Check if the WhatsApp provider is enabled and ready to send messages.
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Send a text message to a specific number.
     *
     * @param string $to
     * @param string $body
     * @param string $eventType
     * @param int|null $paymentId
     * @return array
     */
    public function sendMessage(string $to, string $body, string $eventType = 'manual', ?int $paymentId = null): array;

    /**
     * Normalize phone number format (e.g. to start with 62).
     *
     * @param string|null $number
     * @return string|null
     */
    public function normalizePhone(?string $number): ?string;
}
