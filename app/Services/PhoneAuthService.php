<?php

namespace App\Services;

use App\Models\PhoneVerification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PhoneAuthService
{
    public function __construct(
        private SolapiService $solapi
    ) {}

    public function sendCode(string $phone): void
    {
        $code = (string) random_int(100000, 999999);

        PhoneVerification::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        $sent = $this->solapi->sendVerificationCode($phone, $code);

        if (! $sent) {
            // 발송 API가 아직 설정 안 됐거나 실패한 경우, 로그로 확인할 수 있게 남겨둬요.
            Log::info("[인증번호] {$phone} → {$code}");
        }
    }


    public function verifyCode(string $phone, string $code): bool
    {
        $phone = trim($phone);
        $code = trim($code);

        // 테스트용 우회 코드 - .env에 TESTING_BYPASS_CODE 설정된 동안만 작동해요.
        // 실제 서비스 오픈 전에는 반드시 .env에서 이 값을 지워주세요!
        $bypassCode = config('services.testing.bypass_code');
        if ($bypassCode && $code === $bypassCode) {
            return true;
        }

        $verification = PhoneVerification::where('phone', $phone)
            ->where('code', $code)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (! $verification || $verification->isExpired()) {
            return false;
        }

        $verification->update(['verified_at' => now()]);

        return true;
    }

    public function findOrCreateUser(string $phone, array $profile = []): User
    {
        return User::firstOrCreate(
            ['phone' => $phone],
            array_merge([
                'name' => $profile['name'] ?? '',
                'email' => null,
                'password' => null,
            ], $profile)
        );
    }
}
