<?php

namespace App\Contracts;

interface SmsGatewayInterface
{
    /**
     * Send plain text SMS message.
     */
    public function sendSMS(string $to, string $message): bool;

    /**
     * Send SMS using pattern / lookup template.
     */
    public function sendPattern(string $to, string $patternCode, array $inputData): bool;

    /**
     * Get account credit / balance status (if supported).
     * Returns array with 'credit' and 'currency' or null on failure.
     */
    public function getBalance(): ?array;
}
