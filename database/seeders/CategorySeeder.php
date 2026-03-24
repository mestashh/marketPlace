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
        $category = Category::create([
            'name' => 'Toys',
            'parent_id' => null,
        ]);

        Category::create([
            'name' => 'Cars',
            'parent_id' => $category->id,
        ]);

        Category::create([
            'name' => 'Animals',
            'parent_id' => $category->id,
        ]);
    }
}
