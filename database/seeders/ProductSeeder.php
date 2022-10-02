<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use App\Models\ProductIngredient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now         = Carbon::now()->format('Y-m-d H:i:s');
        $ingredients = Ingredient::all();
        $meatBurger  = Product::create([
            'name'        => 'Meat Burger',
            'description' => 'Meat burger covered with cheese and onion.',
            'price'       => 130,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);
        $chickenBurger = Product::create([
            'name'        => 'Chicken Burger',
            'description' => 'Chicken burger covered with cheese.',
            'price'       => 110,
            'created_at'  => $now,
            'updated_at'  => $now,
        ]);

        $productsIngredients = [
            [
                'product_id'      => $meatBurger->id,
                'ingredient_id'   => $ingredients->where('name', 'Meat')->first()->id,
                'weight_in_grams' => 150,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'product_id'      => $meatBurger->id,
                'ingredient_id'   => $ingredients->where('name', 'Cheese')->first()->id,
                'weight_in_grams' => 30,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'product_id'      => $meatBurger->id,
                'ingredient_id'   => $ingredients->where('name', 'Onion')->first()->id,
                'weight_in_grams' => 20,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'product_id'      => $chickenBurger->id,
                'ingredient_id'   => $ingredients->where('name', 'Chicken')->first()->id,
                'weight_in_grams' => 150,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
            [
                'product_id'      => $chickenBurger->id,
                'ingredient_id'   => $ingredients->where('name', 'Cheese')->first()->id,
                'weight_in_grams' => 30,
                'created_at'      => $now,
                'updated_at'      => $now,
            ],
        ];

        ProductIngredient::insert($productsIngredients);
    }
}
