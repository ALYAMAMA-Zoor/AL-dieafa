<?php

namespace App\Services\Payments\Providers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Services\Payments\Contracts\PaymentProviderInterface;

class StripePayment implements PaymentProviderInterface
{
    public function createPayment(array $data)
    {
        // إعداد Stripe بمفتاح API السري
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // إنشاء Session جديدة للدفع
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $data['currency'] ?? env('STRIPE_CURRENCY', 'usd'),
                    'product_data' => [
                        'name' => $data['description'] ?? 'طلب من المتجر',
                    ],
                    'unit_amount' => intval($data['amount'] * 100), // Stripe يقبل القيم بالسنت
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $data['success_url'] ?? route('payment.callback', ['provider' => 'stripe']) . '?status=success&order_id=' . ($data['order_id'] ?? ''),
            'cancel_url' => $data['cancel_url'] ?? route('payment.cancel', ['provider' => 'stripe']),
        ]);

        // إعادة التوجيه إلى صفحة Stripe Checkout
        return $session->url;
    }

    public function handleCallback(array $payload)
    {
        // في Stripe لا يتم إرسال بيانات كثيرة عبر GET
        // الأفضل استخدام Webhooks، ولكن الآن نتحقق من باراميتر success
        return isset($payload['status']) && $payload['status'] === 'success';
    }
}
