<?php
namespace App\Services\Payments;
use App\Services\Payments\Providers\PaypalPayment;
use App\Services\Payments\Providers\StripePayment;
use App\Services\Payments\Providers\MolliePayment;

class PaymentFactory
{
    public static function make($provider)
    {
        return match ($provider) {
            'paypal' => new PaypalPayment(),
            'stripe' => new StripePayment(),
             'mollie' => new MolliePayment(),
            default => throw new \Exception("مزود الدفع غير معروف: $provider"),
        };
    }
}
