<?php

namespace App\Services\Sms\Drivers;

use App\Contracts\SmsGatewayInterface;
use App\Models\SmsLog;
use App\Services\Settings\SettingService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IpPanelDriver implements SmsGatewayInterface
{
    protected string $apiKey;
    protected string $originator;

    /**
     * Priority list of base URLs for IPPanel REST API.
     */
    protected array $baseUrls = [
        'https://api2.ippanel.com/v1',
        'https://rest.ippanel.com/v1',
    ];

    /**
     * Possible endpoint paths for checking credit in IPPanel API variants.
     */
    protected array $creditEndpoints = [
        'payment/credit',
        'credit',
        'user/credit',
    ];

    public function __construct(SettingService $settingService)
    {
        $this->apiKey = trim($settingService->get('ippanel_api_key', '') ?? '');
        $this->originator = trim($settingService->get('ippanel_originator', '3000505') ?? '3000505');
    }

    /**
     * Standard authentication headers for IPPanel REST API.
     */
    protected function getAuthHeaders(): array
    {
        return [
            'Authorization' => 'AccessKey ' . $this->apiKey,
            'apiKey'        => $this->apiKey,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
    }

    /**
     * Test connection to IPPanel API directly across multiple endpoints.
     */
    public function testConnection(): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'کلید API Key در تنظیمات سیستم وارد نشده است.',
            ];
        }

        $lastError = '';
        $lastStatus = 404;

        foreach ($this->baseUrls as $baseUrl) {
            foreach ($this->creditEndpoints as $endpoint) {
                try {
                    $url = "{$baseUrl}/{$endpoint}";
                    $response = Http::withHeaders($this->getAuthHeaders())
                        ->timeout(5)
                        ->get($url);

                    if ($response->successful()) {
                        $data = $response->json();
                        $credit = $data['data']['credit'] ?? $data['credit'] ?? 0;
                        return [
                            'success' => true,
                            'message' => 'اتصال به IPPanel برقراری شد. موجودی حساب: ' . number_format($credit) . ' ریال',
                            'credit'  => $credit,
                            'domain'  => $baseUrl,
                        ];
                    }

                    $lastStatus = $response->status();
                    $bodyData   = $response->json();
                    $lastError  = $bodyData['message'] ?? $response->body();

                    if ($lastStatus === 401 || $lastStatus === 403) {
                        return [
                            'success' => false,
                            'message' => "خطای احراز هویت (کد {$lastStatus}): کلید API Key نامعتبر است یا در پنل IPPanel محدودیت IP تعریف شده است.",
                        ];
                    }
                } catch (\Exception $e) {
                    $lastError = $e->getMessage();
                }
            }
        }

        return [
            'success' => false,
            'message' => "خطای عدم یافتن مسیر (کد {$lastStatus}): کلید API Key یا آدرس درخواست در سرویس IPPanel تایید نشد. ({$lastError})",
        ];
    }

    /**
     * Send plain text SMS message.
     */
    public function sendSMS(string $to, string $message): bool
    {
        if (empty($this->apiKey)) {
            Log::error('IPPanel SMS failed: API key is empty.');
            $this->logSms($to, 'text', null, $message, 'failed', null, 'API Key missing');
            return false;
        }

        $payload = [
            'originator' => $this->originator,
            'recipients' => [$to],
            'message'    => $message,
        ];

        foreach ($this->baseUrls as $baseUrl) {
            try {
                $response = Http::withHeaders($this->getAuthHeaders())
                    ->timeout(6)
                    ->post("{$baseUrl}/messages", $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $messageId = $data['data']['message_id'] ?? $data['message_id'] ?? null;
                    $this->logSms($to, 'text', null, $message, 'sent', (string) $messageId);
                    return true;
                }

                $error = $response->body();
                if (in_array($response->status(), [502, 503, 504])) {
                    continue; // retry next domain
                }

                $this->logSms($to, 'text', null, $message, 'failed', null, $error);
                return false;
            } catch (\Exception $e) {
                Log::error("IPPanel SMS Exception ({$baseUrl}): " . $e->getMessage());
            }
        }

        $this->logSms($to, 'text', null, $message, 'failed', null, 'همه سرورهای IPPanel خارج از دسترس بودند.');
        return false;
    }

    /**
     * Send SMS using IPPanel pattern API.
     */
    public function sendPattern(string $to, string $patternCode, array $inputData): bool
    {
        if (empty($this->apiKey)) {
            Log::error('IPPanel Pattern SMS failed: API key is empty.');
            $this->logSms($to, 'pattern', $patternCode, json_encode($inputData), 'failed', null, 'API Key missing');
            return false;
        }

        $payload = [
            'pattern_code' => $patternCode,
            'originator'   => $this->originator,
            'recipient'    => $to,
            'values'       => (object) $inputData,
        ];

        foreach ($this->baseUrls as $baseUrl) {
            try {
                $response = Http::withHeaders($this->getAuthHeaders())
                    ->timeout(6)
                    ->post("{$baseUrl}/messages/patterns/send", $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    $messageId = $data['data']['message_id'] ?? $data['message_id'] ?? null;
                    $this->logSms($to, 'pattern', $patternCode, json_encode($inputData), 'sent', (string) $messageId);
                    return true;
                }

                $error = $response->body();
                if (in_array($response->status(), [502, 503, 504])) {
                    continue; // retry next domain
                }

                $this->logSms($to, 'pattern', $patternCode, json_encode($inputData), 'failed', null, $error);
                return false;
            } catch (\Exception $e) {
                Log::error("IPPanel Pattern Exception ({$baseUrl}): " . $e->getMessage());
            }
        }

        $this->logSms($to, 'pattern', $patternCode, json_encode($inputData), 'failed', null, 'همه سرورهای IPPanel خارج از دسترس بودند.');
        return false;
    }

    /**
     * Get account credit / balance status.
     */
    public function getBalance(): ?array
    {
        $testResult = $this->testConnection();
        if ($testResult['success']) {
            return [
                'credit'   => $testResult['credit'],
                'currency' => 'ریال',
            ];
        }

        return null;
    }

    /**
     * Log SMS attempt to database.
     */
    protected function logSms(string $phone, string $type, ?string $patternCode, string $message, string $status, ?string $messageId = null, ?string $error = null): void
    {
        try {
            $maskedMessage = preg_replace('/\b\d{4,6}\b/', '****', $message);

            SmsLog::create([
                'phone'        => $phone,
                'driver'       => 'ippanel',
                'type'         => $type,
                'pattern_code' => $patternCode,
                'message'      => $maskedMessage,
                'status'       => $status,
                'message_id'   => $messageId,
                'error_message'=> $error,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to write SmsLog: " . $e->getMessage());
        }
    }
}
