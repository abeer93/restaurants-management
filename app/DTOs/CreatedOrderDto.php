<?php

namespace App\DTOs;

use App\Models\Order;

final class CreatedOrderDto
{
    public function __construct(private int $orderId, private float $totalPrice)
    {
    }

    public static function createFromOrder(Order $order): CreatedOrderDto
    {
        return new self($order->id, $order->total_price);
    }

    /**
     * @return array<string,int|float>
     */
    public function transformToArray(): array
    {
        return [
            'order_id'    => $this->orderId,
            'total_price' => $this->totalPrice,
        ];
    }
}