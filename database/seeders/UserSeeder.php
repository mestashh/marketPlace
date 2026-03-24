<?php

namespace Database\Seeders;

use App\Enums\AdminRoleEnum;
use App\Models\Address;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate([
            'first_name' => 'Ruslan',
            'last_name' => 'Ivanov',
            'email' => 'zeuszeus1973@gmail.com',
            'password' => Hash::make('password123'),
            'phone' => '88067590470',
        ]);
        Admin::updateOrCreate(
            [
                'user_id' => $admin->id,
                'role' => AdminRoleEnum::SUPER_ADMIN->value,
            ]);
        User::factory(10)->has(Address::factory()->count(2))->create();
    }
}
