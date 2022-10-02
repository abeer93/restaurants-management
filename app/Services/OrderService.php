<?php 

namespace App\Services;

use App\DTOs\CreatedOrderDto;
use App\Events\OrderCreated;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

final class OrderService
{
    public function __construct(private Order $orderModel, private Product $productModel)
    {
    }

    /**
     * @param array<string,integer[]> $data
     */
    public function addOrder(array $data): CreatedOrderDto|false
    {
        $collectedData = collect($data);
        $productIDs    = $collectedData->pluck('product_id')->toArray();
        $products      = $this->productModel->whereIn('id', $productIDs)->get();
        
        $orderDetails    = [];
        $orderTotalPrice = 0;
        foreach ($products as $product) {
            $pickedProduct = $collectedData->where('product_id', $product->id)->first();
            $productPrice  = $pickedProduct['quantity'] * $product->price;
            array_push($orderDetails, [
                'product_id'  => $product->id,
                'quantity'    => $pickedProduct['quantity'],
                'total_price' => $productPrice
            ]);

            $orderTotalPrice += $productPrice;
        }

        DB::beginTransaction();
        try {
            $createdOrder = $this->orderModel->create(['total_price' => $orderTotalPrice]);
            $createdOrder->products()->attach($orderDetails);

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();

            Log::debug('error while creating new order with message ===> ' . $th->getMessage());

            return false;
        }

        OrderCreated::dispatch($createdOrder);

        return CreatedOrderDto::createFromOrder($createdOrder);
    }
}
