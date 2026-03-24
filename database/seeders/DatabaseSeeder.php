<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(
            [
                UserSeeder::class,
                PayoutMethodSeeder::class,
                CategorySeeder::class,
                SellerSeeder::class,
                OrderSeeder::class,
                AdminSeeder::class,
                PaymentMethodSeeder::class,
                CartItemSeeder::class,
                PaymentSeeder::class,
                ReviewSeeder::class,
                ConversationSeeder::class,
                PayoutSeeder::class,
            ]);
    }
}
