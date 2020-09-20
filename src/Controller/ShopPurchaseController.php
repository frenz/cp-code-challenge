<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Purchase;
use App\Exception\ProductNotFoundException;
use App\Exception\ProductOutOfStockException;
use App\Exception\TokenNotValidException;
use App\Service\PurchaseProduct;
use Exception;
use Firebase\JWT\ExpiredException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

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
        try {
            if (empty($body) || !is_string($body->purchase_token ?? null) || !is_int($body->product_id ?? null)) {
                throw new Exception('Invalid request');
            }
            $token = $body->purchase_token;
            $productId = (int)$body->product_id;

            try {
                $purchase = $this->purchaseProduct->withNameAndPurchaseToken($token, $productId);
            } catch (ProductNotFoundException $e) {
                return $this->json(['error' => $e->getMessage()], Response::HTTP_OK);
            } catch (ProductOutOfStockException $e) {
                return $this->json(['error' => $e->getMessage()], Response::HTTP_OK);
            } catch (TokenNotValidException $e) {
                return $this->json(['error' => $e->getMessage()], Response::HTTP_UNAUTHORIZED);
            } catch (ExpiredException $e) {
                return $this->json(['error' => 'Token expired'], Response::HTTP_UNAUTHORIZED);
            }
        } catch (Throwable $e) {
            $message = $e->getMessage();
            return $this->json(['error' => $message], Response::HTTP_BAD_REQUEST);
        }
        return $this->json(['success' => $purchase instanceof Purchase]);
    }
}
