<?php

namespace App\TestProject\Application\Service;

use App\TestProject\Domain\Model\Payment\PaymentProcessorInterface;
use Doctrine\DBAL\Exception;

class PurchaseService
{
    public function __construct(
        private OrderService $orderService,
        /**
         * @var PaymentProcessorInterface[]
         */
        private array $paymentProcessors,
    ) {}

    public function makePurchase(int $productId, string $taxNumber, ?string $couponCode, string $paymentProcessor): void
    {
        $orderSum = $this->orderService->calculate($productId, $taxNumber, $couponCode);

        foreach ($this->paymentProcessors as $processor) {
            if ($processor->isSupported($paymentProcessor)) {
                $processor->pay($orderSum);
                return;
            }
        }

        throw new Exception('wrong paymentProcessor');
    }
}