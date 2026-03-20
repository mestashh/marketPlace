<?php

namespace Database\Seeders;

use App\Models\SellerPayoutMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellerPayoutMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SellerPayoutMethod::factory(5)->create();
    }
}
