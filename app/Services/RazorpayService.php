<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class RazorpayService
{
    public function isEnabled(): bool
    {
        return Setting::getValue('razorpay_enabled', '0') === '1'
            && $this->keyId()
            && $this->keySecret();
    }

    public function mode(): string
    {
        $mode = Setting::getValue('razorpay_mode', 'test');

        return $mode === 'live' ? 'live' : 'test';
    }

    public function keyId(): string
    {
        return (string) Setting::getValue(
            $this->mode() === 'live' ? 'razorpay_key_id_live' : 'razorpay_key_id_test',
            ''
        );
    }

    public function keySecret(): string
    {
        return (string) Setting::getValue(
            $this->mode() === 'live' ? 'razorpay_key_secret_live' : 'razorpay_key_secret_test',
            ''
        );
    }

    public function createOrder(int $amountPaise, string $receipt, array $notes = []): array
    {
        $response = Http::withBasicAuth($this->keyId(), $this->keySecret())
            ->acceptJson()
            ->asJson()
            ->timeout(30)
            ->post('https://api.razorpay.com/v1/orders', [
                'amount' => $amountPaise,
                'currency' => 'INR',
                'receipt' => $receipt,
                'payment_capture' => 1,
                'notes' => $notes,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException(
                'Razorpay order failed: ' . ($response->json('error.description') ?: $response->body())
            );
        }

        return $response->json();
    }

    public function verifyPaymentSignature(string $orderId, string $paymentId, string $signature): bool
    {
        $expected = hash_hmac('sha256', $orderId . '|' . $paymentId, $this->keySecret());

        return hash_equals($expected, $signature);
    }

    public function fetchPayment(string $paymentId): array
    {
        $response = Http::withBasicAuth($this->keyId(), $this->keySecret())
            ->acceptJson()
            ->timeout(30)
            ->get('https://api.razorpay.com/v1/payments/' . $paymentId);

        if (! $response->successful()) {
            throw new RuntimeException(
                'Razorpay payment fetch failed: ' . ($response->json('error.description') ?: $response->body())
            );
        }

        return $response->json();
    }

    public function testConnection(): array
    {
        if (! $this->keyId() || ! $this->keySecret()) {
            throw new RuntimeException('Razorpay Key ID and Secret are required for the selected mode.');
        }

        $response = Http::withBasicAuth($this->keyId(), $this->keySecret())
            ->acceptJson()
            ->timeout(20)
            ->get('https://api.razorpay.com/v1/orders', [
                'count' => 1,
            ]);

        if ($response->status() === 401) {
            throw new RuntimeException('Invalid Razorpay credentials for ' . $this->mode() . ' mode.');
        }

        if (! $response->successful()) {
            throw new RuntimeException(
                'Razorpay API error: ' . ($response->json('error.description') ?: $response->body())
            );
        }

        return [
            'ok' => true,
            'mode' => $this->mode(),
            'key_id' => $this->keyId(),
            'message' => 'Razorpay ' . strtoupper($this->mode()) . ' credentials verified successfully.',
        ];
    }
}
