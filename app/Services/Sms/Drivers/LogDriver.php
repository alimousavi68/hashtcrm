<?php

namespace App\Services\Sms\Drivers;

use App\Contracts\SmsGatewayInterface;
use App\Models\SmsLog;
use Illuminate\Support\Facades\Log;

class LogDriver implements SmsGatewayInterface
{
    public function sendSMS(string $to, string $message): bool
    {
        Log::info("SMS LogDriver [Text] -> To: {$to} | Message: {$message}");
        $this->logSms($to, 'text', null, $message);
        return true;
    }

    public function sendPattern(string $to, string $patternCode, array $inputData): bool
    {
        $payload = json_encode($inputData, JSON_UNESCAPED_UNICODE);
        Log::info("SMS LogDriver [Pattern {$patternCode}] -> To: {$to} | Payload: {$payload}");
        $this->logSms($to, 'pattern', $patternCode, $payload);
        return true;
    }

    public function getBalance(): ?array
    {
        return [
            'credit' => 999999,
            'currency' => 'IRR (Simulated Test Mode)',
        ];
    }

    protected function logSms(string $phone, string $type, ?string $patternCode, string $message): void
    {
        try {
            SmsLog::create([
                'phone' => $phone,
                'driver' => 'log',
                'type' => $type,
                'pattern_code' => $patternCode,
                'message' => $message,
                'status' => 'sent',
                'message_id' => 'SIMULATED-' . uniqid(),
            ]);
        } catch (\Exception $e) {
            // ignore in test
        }
    }
}
