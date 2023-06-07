<?php

namespace App\TestProject\Infrastructure\Payment;

use App\TestProject\Domain\Model\Payment\PaymentProcessorInterface;
use Exception;


class StripePaymentProcessorDecorator implements PaymentProcessorInterface
{
    public function __construct(
        private StripePaymentProcessor $stripePaymentProcessor,
    )
    {}

    public function isSupported(string $paymentProcessor): bool
    {
        return $paymentProcessor === 'stripe';
    }


    public function pay(int $price): void
    {
        $payment = $this->stripePaymentProcessor->processPayment($price);

        if (!$payment) {
            throw new Exception('price too low');
        }
    }
}