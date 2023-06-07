<?php

namespace App\TestProject\Infrastructure\Controller;

use App\Form\PurchaseFormType;
use App\TestProject\Application\Service\PurchaseService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    public function __construct(
        private PurchaseService $purchaseService,
    )
    {}

    #[Route('/api/purchase', name: 'app_purchase', methods: ['POST'])]
    public function purchase(Request $request): JsonResponse
    {
        $productId = $request->request->get('productId');
        $taxNumber = $request->request->get('taxNumber');
        $couponCode = $request->request->get('couponCode');
        $paymentProcessor = $request->request->get('paymentProcessor');

        if (!$productId) {
            throw new Exception('set productId');
        }
        $this->purchaseService->makePurchase($productId, $taxNumber, $couponCode, $paymentProcessor);

        return $this->json('success');
    }

    #[Route('/api/purchaseFromForm', methods: ['GET', 'POST'])]
    public function purchaseFromForm()
    {
        $form = $this->createForm(PurchaseFormType::class);
        dump($form);

        return $this->render('Form/purchase.form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
