<?php

namespace App\Services;

use App\Models\PhoneVerification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PhoneAuthService
{
    public function sendCode(string $phone): void
    {
        $code = (string) random_int(100000, 999999);

        PhoneVerification::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
        ]);

        Log::info("[인증번호] {$phone} → {$code}");
    }

    public function verifyCode(string $phone, string $code): bool
    {
        $phone = trim($phone);
        $code = trim($code);

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
