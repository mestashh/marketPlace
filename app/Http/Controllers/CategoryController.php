<?php

namespace App\Http\Controllers;

use App\Exceptions\CategoryExistsException;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(private readonly CategoryService $categoryService)
    {
        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::paginate(20);

        return CategoryResource::collection($category);
    }

    /**
     * Store a newly created resource in storage.
     * @throws CategoryExistsException
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->store($request->validated());

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     * @throws CategoryExistsException
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category = $this->categoryService->update($request->validated(), $category);

        return new CategoryResource($category);
    }
}
