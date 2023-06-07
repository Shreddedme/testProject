<?php

namespace App\TestProject\Application\Service;

use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\TestProject\Domain\TestEntity\Coupon;
use App\TestProject\Domain\TestEntity\Product;
use Doctrine\DBAL\Exception;


class OrderService
{
    public function __construct(
        private ProductRepository $productRepository,
        private TaxService        $taxService,
        private CouponService     $couponService,
        private CouponRepository  $couponRepository,
    )
    {}

    public function calculate(int $id, string $taxNumber, ?string $couponCode): float
    {
        $product = $this->productRepository->find($id);

        if(!$product instanceof Product) {
            throw new Exception('wrong Id');
        }

        $taxRate = $this->taxService->getTax($taxNumber);
        $basePrice = $product->getPrice();

        if ($couponCode !== null) {
            $coupon = $this->couponRepository->findByName($couponCode);
            if (!$coupon instanceof Coupon) {
                throw new Exception('wrong coupon');
            }
            $price = $this->couponService->getPriceWithCoupon($coupon, $product) + ($basePrice * $taxRate);
        } else {
            $price = $basePrice + ($basePrice * $taxRate);
        }

        return $price;
    }
}