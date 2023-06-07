<?php

namespace App\TestProject\Application\Service;

use App\TestProject\Domain\TestEntity\Coupon;
use App\TestProject\Domain\TestEntity\Product;
use Doctrine\DBAL\Exception;

class CouponService
{
    public function getPriceWithCoupon(Coupon $coupon, Product $product): float
    {
        if ($coupon->getName()) {
            if ($coupon->getType() === 'fixed') {
                return $product->getPrice() - $coupon->getValue();
            }

            if ($coupon->getType() === 'percentage') {
                return $product->getPrice() - ($product->getPrice() * $coupon->getValue() / 100);
            }
        }

        throw new Exception('wrong couponCode');
    }
}