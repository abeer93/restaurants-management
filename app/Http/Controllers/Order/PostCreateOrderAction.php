<?php

declare(strict_types=1);

namespace App\Http\Controllers\Order;

use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *    title="Orders Service APIs",
 *    version="1.0.0",
 * )
 */
final class PostCreateOrderAction
{
    public function __construct(private OrderService $orderService)
    {
    }

    /**
     * @OA\PathItem(path="/api")
     * @OA\Post(
     * path="/api/orders",
     * summary="add new order",
     * description="add Order",
     * tags={"orders"},
     * @OA\RequestBody(
     *   required=true,
     *   @OA\MediaType(
     *      mediaType="application/json",
     *      @OA\Schema(
     *          @OA\Property(
     *              property="products",
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="product_id", type="integer", example=10),
     *                  @OA\Property(property="quantity", type="integer", example=3),
     *              )
     *          ),
     *      )
     *  )
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Created Successfully."),
     *       @OA\Property(property="order_id", type="integer", example=13),
     *       @OA\Property(property="total_price", type="integer", example=350),
     *     )
     *  ),
     * @OA\Response(
     *    response=400,
     *    description="Failed",
     *    @OA\JsonContent(
     *          @OA\Property(property="message", type="string", example="Sorry, something wrong happened please try again later."),
     *     )
     *  ),
     * @OA\Response(
     *    response=422,
     *    description="Failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="The selected products.0.product_id is invalid. (and 1 more error)"),
     *       @OA\Property(
     *              property="errors",
     *              type="array",
     *              @OA\Items(
     *                  @OA\Property(property="products.0.product_id", type="string", example="The selected products.0.product_id is invalid."),
     *                  @OA\Property(property="products.0.quantity", type="string", example={"The products.0.quantity must be an integer.", "The products.0.quantity must be at least 1."}),
     *              )
     *       ),
     *     )
     *  ),
     * )
     */
    public function __invoke(StoreOrderRequest $request): JsonResponse
    {
        $createdOrder = $this->orderService->addOrder($request->products);
        if (! $createdOrder) {
            return new JsonResponse(['message' => 'Sorry, something wrong happened please try again later.'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(array_merge(['message' => 'Created Successfully.'], $createdOrder->transformToArray()), Response::HTTP_CREATED);
    }
}
