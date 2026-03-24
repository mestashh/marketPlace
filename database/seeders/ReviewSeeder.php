<?php

namespace Database\Seeders;

use App\Enums\StatusEnum;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::find(5);
        $products = Product::all();
        foreach ($products as $product) {
            Review::factory()
                ->count(1)
                ->for($user)
                ->for($product)
                ->create();
        }

    }
}
