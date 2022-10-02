<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\NotifyOperationWithLowIngredientsStockMail;
use App\Models\Ingredient;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class UpdateIngredientStock implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private Ingredient $ingredient)
    {
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $orderDetails = $event->order->orderProducts()
                                ->with(['product', 'product.productIngredients', 'product.productIngredients.ingredient'])
                                ->get();

        $ingredientData = [];
        foreach ($orderDetails as $item) {
            $product = $item->product;
            foreach ($product->productIngredients as $productIngredient) {
                $consumedWeight = $item->quantity * $productIngredient->weight_in_grams;
                $ingredient     = $productIngredient->ingredient;
                $shouldNotify   = (int) ((($ingredient->available_weight_in_grams - $consumedWeight) / $ingredient->weight_in_grams) * 100) <= 50;

                if (isset($ingredientData[$ingredient->id])) {
                    $ingredientData[$ingredient->id]['available_weight'] -= $consumedWeight;
                } else {
                    $ingredientData[$ingredient->id] = [
                        'name'             => $ingredient->name,
                        'original_weight'  => $ingredient->weight_in_grams,
                        'available_weight' => $ingredient->available_weight_in_grams - $consumedWeight,
                        'should_notify'    => $shouldNotify,
                    ];
                }
            }
        }

        DB::beginTransaction();
        try {
            foreach ($ingredientData as $ingredientId => $ingredient) {
                $this->ingredient->where('id', $ingredientId)->update(['available_weight_in_grams' => $ingredient['available_weight']]);
            }

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();

            Log::debug("Failed to update ingredient after creating order with id = " . $event->order->id . " DB error with message ===> " . $th->getMessage());

            return false;
        }

        $ingredientsWithLowStock = collect($ingredientData)->where('should_notify', true)->toArray();
        if (count($ingredientsWithLowStock) > 0) {
            Mail::to('test@gmail.com')->send(new NotifyOperationWithLowIngredientsStockMail($ingredientsWithLowStock));
        }

        return true;
    }
}
