<?php

namespace App\TestProject\Domain\Model\Payment;

interface PaymentProcessorInterface
{
    public function isSupported(string $paymentProcessor): bool;

    public function pay(int $price): void;

}