<?php

namespace App\Tests\OrderTest;

use App\Repository\CouponRepository;
use App\Repository\ProductRepository;
use App\TestProject\Application\Service\CouponService;
use App\TestProject\Application\Service\OrderService;
use App\TestProject\Application\Service\TaxService;
use App\TestProject\Domain\TestEntity\Coupon;
use App\TestProject\Domain\TestEntity\Product;
use PHPUnit\Framework\TestCase;

class OrderServiceTest extends TestCase
{
    private OrderService $orderService;
    private ProductRepository $productRepository;
    private TaxService        $taxService;
    private CouponService     $couponService;
    private CouponRepository  $couponRepository;

    protected function setUp(): void
    {
        $product = new Product();
        $product->setPrice(100);
        $this->productRepository = $this->createMock(ProductRepository::class);
        $this->productRepository->expects($this->once())->method('find')->willReturn($product);

        $this->taxService = $this->createMock(TaxService::class);
        $this->taxService->expects($this->once())->method('getTax')->willReturn(0.22);

        $this->couponService = $this->createMock(CouponService::class);

        $this->couponRepository = $this->createMock(CouponRepository::class);

        $this->orderService = new OrderService(
            $this->productRepository,
            $this->taxService,
            $this->couponService,
            $this->couponRepository
        );
    }

    /**
     * @dataProvider orderDataProvider
     */
    public function testCalculateOrderPrice(int $productId, string $taxNumber, ?string $couponCode, float $expectedPrice): void
    {
        if ($couponCode != null) {
            $this->couponService->expects($this->once())->method('getPriceWithCoupon')->willReturn(90.0);
            $this->couponRepository->expects($this->once())->method('findByName')->willReturn(new Coupon());

        } else {
            $this->couponService->expects($this->never())->method('getPriceWithCoupon');
            $this->couponRepository->expects($this->never())->method('findByName');

        }

        $actualPrice = $this->orderService->calculate($productId, $taxNumber, $couponCode);

        $this->assertEquals($expectedPrice, $actualPrice);
    }

    public function orderDataProvider(): array
    {
        return [
            [1, 'IT12345678901', 'IT10', 112],
            [1, 'IT12345678901', null, 122],
        ];
    }
}