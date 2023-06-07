<?php

namespace App\TestProject\Infrastructure\Controller;

use App\Form\OrderFormType;
use App\TestProject\Application\Service\OrderService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{

    public function __construct(
        private OrderService $orderService,
    )
    {}

    #[Route('/api/order', name: 'app_calculate_price', methods: ['GET'])]
    public function calculate(Request $request): JsonResponse
    {
        $productId = $request->query->get('productId');
        $taxNumber = $request->query->get('taxNumber');
        $couponCode = $request->query->get('couponCode');

        if (!$productId) {
            throw new Exception('set productId');
        }

        $orderSum = $this->orderService->calculate($productId, $taxNumber, $couponCode);

        $orderData = [
            'product' => $productId,
            'taxNumber' => $taxNumber,
            'couponCode' => $couponCode,
            'orderSum' => $orderSum,
        ];

      return $this->json($orderData);
    }

    #[Route('/api/calculateFromForm', methods: ['GET'])]
    public function calculateFromForm(): Response
    {
        $form = $this->createForm(OrderFormType::class);
        dump($form);

        return $this->render('Form/order.form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
