<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $admin = User::updateOrCreate([
            'name' => 'mestash',
            'email' => 'zeuszeus1973@gmail.com',
            'phone' => '89162406172',
            'password' => Hash::make('M3st@sh1!'),
        ]);
        Admin::updateOrCreate([
            'user_id' => $admin->id,
            'role' => 'admin',
        ]);
    }
}
