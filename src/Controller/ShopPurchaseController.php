<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Purchase;
use App\Service\PurchaseProduct;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/shop/purchase", name="shop_purchase", methods={"POST"}))
 */
class ShopPurchaseController extends AbstractController
{
    private PurchaseProduct $purchaseProduct;

    public function __construct(PurchaseProduct $purchaseProduct)
    {
        $this->purchaseProduct = $purchaseProduct;
    }


    public function __invoke(Request $request): JsonResponse
    {
        $body = json_decode($request->getContent()) ?? [];
        $token = $body->queue_token;
        $productId = (int)$request->get('product_id');

        $purchase = $this->purchaseProduct->withNameAndPurchaseToken($token, $productId);

        return $this->json(['success' => $purchase instanceof Purchase]);
    }
}
