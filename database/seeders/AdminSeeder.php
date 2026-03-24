<?php

namespace Database\Seeders;

use App\Enums\AdminRoleEnum;
use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'user_id' => 2,
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);
        Admin::factory(5)->create();
    }
}
