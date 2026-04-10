<?php

namespace App\Services;

use App\Exceptions\CategoryExistsException;
use App\Models\Category;

class CategoryService
{
    /**
     * @throws CategoryExistsException
     */
    public function store(array $data): Category
    {
        if (Category::where('name', $data['name'])->exists()) {
            throw new CategoryExistsException;
        }
        return Category::create($data);
    }

    /**
     * @throws CategoryExistsException
     */
    public function update(array $data, Category $category): Category
    {
        if (Category::where('name', $data['name'])
            ->where('id', '!=', $category->id)
            ->exists()) {
            throw new CategoryExistsException;
        }
        $category->update($data);

        return $category;
    }
}
