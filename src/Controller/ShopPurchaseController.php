<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Purchase;
use App\Exception\ProductNotFoundException;
use App\Exception\ProductOutOfStockException;
use App\Exception\TokenNotValidException;
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
        $token = $body->purchase_token;
        $productId = (int)$body->product_id;

        try {
            $purchase = $this->purchaseProduct->withNameAndPurchaseToken($token, $productId);
        } catch (ProductNotFoundException $e) {
        } catch (ProductOutOfStockException $e) {
        } catch (TokenNotValidException $e) {
        }

        return $this->json(['success' => $purchase instanceof Purchase]);
    }
}
