<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now          = Carbon::now()->format('Y-m-d H:i:s');
        $ingredients  = [
            [
                'name'                      => 'Chicken',
                'weight_in_grams'           => 1000,
                'available_weight_in_grams' => 500,
                'created_at'                => $now,
                'updated_at'                => $now,
            ],
            [
                'name'                      => 'Meat',
                'weight_in_grams'           => 1000,
                'available_weight_in_grams' => 500,
                'created_at'                => $now,
                'updated_at'                => $now,
            ],
            [
                'name'                      => 'Cheese',
                'weight_in_grams'           => 1000,
                'available_weight_in_grams' => 500,
                'created_at'                => $now,
                'updated_at'                => $now,
            ],
            [
                'name'                      => 'Onion',
                'weight_in_grams'           => 1000,
                'available_weight_in_grams' => 700,
                'created_at'                => $now,
                'updated_at'                => $now,
            ],
        ];

        Ingredient::insert($ingredients);
    }
}
