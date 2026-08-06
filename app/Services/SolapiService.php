<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SolapiService
{
    private function authHeader(): string
    {
        $apiKey = config('services.solapi.api_key');
        $apiSecret = config('services.solapi.api_secret');

        $dateTime = gmdate('Y-m-d\TH:i:s\Z');
        $salt = bin2hex(random_bytes(16));
        $signature = hash_hmac('sha256', $dateTime.$salt, $apiSecret);

        return "HMAC-SHA256 apiKey={$apiKey}, date={$dateTime}, salt={$salt}, signature={$signature}";
    }

    /**
     * 카카오 알림톡으로 인증번호를 보내요. 알림톡이 실패하면(아직 템플릿 승인 전 등)
     * 자동으로 문자(SMS)로 대체 발송해요.
     */
    public function sendVerificationCode(string $phone, string $code): bool
    {
        $pfId = config('services.solapi.kakao_pf_id');
        $templateId = config('services.solapi.template_verification');
        $from = config('services.solapi.sender_number');

        if (! config('services.solapi.api_key') || ! $from) {
            Log::info("[인증번호 - 발송 미설정] {$phone} → {$code}");
            return false;
        }

        // 알림톡 템플릿까지 준비됐으면 알림톡으로, 아니면 바로 문자로
        if ($pfId && $templateId) {
            $sent = $this->send([
                'to' => $phone,
                'from' => $from,
                'kakaoOptions' => [
                    'pfId' => $pfId,
                    'templateId' => $templateId,
                    'variables' => [
                        '#{인증번호}' => $code,
                    ],
                ],
            ]);

            if ($sent) {
                return true;
            }

            Log::warning("[알림톡 발송 실패, SMS로 대체] {$phone}");
        }

        return $this->send([
            'to' => $phone,
            'from' => $from,
            'text' => "[MEEWV] 인증번호는 {$code} 입니다. (5분 이내 입력)",
        ]);
    }

    private function send(array $message): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->authHeader(),
                'Content-Type' => 'application/json',
            ])->post('https://api.solapi.com/messages/v4/send', [
                'message' => $message,
            ]);

            if (! $response->successful()) {
                Log::error('[솔라피 발송 실패]', ['body' => $response->body()]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('[솔라피 발송 예외]', ['message' => $e->getMessage()]);
            return false;
        }
    }
}