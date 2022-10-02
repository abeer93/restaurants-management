<?php

declare(strict_types=1);

namespace Tests\Feature\User\Http\Actions;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PostCreateOrderActionTest extends TestCase
{
    use RefreshDatabase;

    public function testWithoutSendingBodyDataWillReturnValidationError(): void
    {
        $this->post(route('orders.store'), [], ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The products field is required.',
                'errors'  => [
                    'products' => ['The products field is required.']
                ]
            ]);
    }

    public function testWithSendingInvalidProductIdWillReturnValidationError(): void
    {
        $bodyData = [
            'products' => [
                [
                    'product_id' => 13,
                    'quantity'   => 1
                ],
            ],
        ];
        $this->post(route('orders.store'), $bodyData, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The selected products.0.product_id is invalid.',
                'errors'  => [
                    'products.0.product_id' => ['The selected products.0.product_id is invalid.']
                ]
            ]);
    }

    public function testWithSendingInvalidQuantityTypeWillReturnValidationError(): void
    {
        $bodyData = [
            'products' => [
                [
                    'product_id' => 13,
                    'quantity'   => "string"
                ],
            ],
        ];
        $this->post(route('orders.store'), $bodyData, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The selected products.0.product_id is invalid. (and 1 more error)',
                'errors'  => [
                    'products.0.quantity' => ['The products.0.quantity must be an integer.']
                ]
            ]);
    }

    public function testWithSendingInvalidQuantityValueWillReturnValidationError(): void
    {
        $bodyData = [
            'products' => [
                [
                    'product_id' => 13,
                    'quantity'   => -1
                ],
            ],
        ];
        $this->post(route('orders.store'), $bodyData, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJson([
                'message' => 'The selected products.0.product_id is invalid. (and 1 more error)',
                'errors'  => [
                    'products.0.quantity' => ['The products.0.quantity must be at least 1.']
                ]
            ]);
    }

    public function testSendingValidDataWillReturnSuccess(): void
    {
        Mail::fake();

        $this->artisan('db:seed');
        $products = Product::all();
        $bodyData = [
            'products' => [
                [
                    'product_id' => $products->first()->id,
                    'quantity'   => 2,
                ],
                [
                    'product_id' => $products->reverse()->first()->id,
                    'quantity'   => 1,
                ],
            ],
        ];
        $response = $this->post(route('orders.store'), $bodyData, ['Accept' => 'application/json']);
        $response->assertStatus(Response::HTTP_CREATED);
        $order = Order::first();
        $response->assertJson([
            'message'     => 'Created Successfully.',
            'order_id'    => $order->id,
            'total_price' => $order->total_price,
        ]);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders_products', [
            'order_id'    => $order->id,
            'product_id'  => $products->first()->id,
            'quantity'    => 2,
            'total_price' => $products->first()->price * 2,
        ]);
        $this->assertDatabaseHas('orders_products', [
            'order_id'    => $order->id,
            'product_id'  => $products->reverse()->first()->id,
            'quantity'    => 1,
            'total_price' => $products->reverse()->first()->price,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name'                      => 'Meat',
            'available_weight_in_grams' => 200,
        ]);
        $this->assertDatabaseHas('ingredients', [
            'name'                      => 'Chicken',
            'available_weight_in_grams' => 350,
        ]);
    }
}
