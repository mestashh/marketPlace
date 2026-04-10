<?php

namespace Tests\Feature;

use App\Enums\StatusEnum;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Seller;
use App\Models\Shop;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    private function createProduct(StatusEnum $status): array
    {
        $seller = Seller::factory()->create();
        $shop = Shop::create([
            'name' => 'Test',
            'seller_id' => $seller->id,
            'description' => 'Test',
        ]);
        $category = Category::create([
            'name' => 'Test',
            'parent_id' => null,
        ]);

        $product = Product::create([
            'name' => 'Test',
            'category_id' => $category->id,
            'shop_id' => $shop->id,
            'quantity' => 100,
            'access_status' => $status->value,
        ]);

        $productImage = ProductImage::factory()->create([
            'product_id' => $product->id,
            'access_status' => $status->value,
            'is_main' => true,
        ]);

        return [$product, $seller];
    }
}
