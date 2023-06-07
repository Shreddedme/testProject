<?php

namespace App\TestProject\Infrastructure\Payment;

use App\TestProject\Domain\Model\Payment\PaymentProcessorInterface;

class PaypalPaymentProcessorDecorator implements PaymentProcessorInterface
{
    public function __construct(
        private PaypalPaymentProcessor $paypalPaymentProcessor,
    )
    {}

    public function isSupported(string $paymentProcessor): bool
    {
        return $paymentProcessor === 'paypal';
    }

    public function pay(int $price): void
    {
        $this->paypalPaymentProcessor->pay($price);
    }
}