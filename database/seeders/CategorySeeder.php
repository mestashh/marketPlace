<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parents = Category::factory(10)->create();

        foreach ($parents as $parent) {
            Category::factory(3)->create([
                'parent_id' => $parent->id,
            ]);
        }
        Category::factory(10)->create();
    }
}
