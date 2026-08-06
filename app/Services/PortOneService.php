<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PortOneService
{
    /**
     * 포트원 서버 API로 결제 상태를 직접 조회해서 검증해요.
     * 프론트에서 "성공했다"고 하는 걸 그대로 믿지 않고, 서버가 다시 확인하는 절차예요.
     */
    public function verifyPayment(string $paymentId): ?array
    {
        $secret = config('services.portone.api_secret');

        if (! $secret) {
            Log::warning('[PortOne] API_SECRET이 설정되지 않았어요.');
            return null;
        }

        $response = Http::withHeaders([
            'Authorization' => 'PortOne '.$secret,
        ])->get("https://api.portone.io/payments/{$paymentId}");

        if (! $response->successful()) {
            Log::warning('[PortOne] 결제 조회 실패', ['paymentId' => $paymentId, 'body' => $response->body()]);
            return null;
        }

        return $response->json();
    }

    /**
     * 결제 상태가 실제로 "완료"이고, 금액도 우리가 요청한 금액과 정확히 일치하는지 확인해요.
     */
    public function isPaid(array $paymentData, int $expectedAmount): bool
    {
        $status = $paymentData['status'] ?? null;
        $paidAmount = (int) ($paymentData['amount']['total'] ?? 0);

        return $status === 'PAID' && $paidAmount === $expectedAmount;
    }
}