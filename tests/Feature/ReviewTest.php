<?php

namespace Tests\Feature;

use App\Enums\OrderStatusEnum;
use App\Enums\StatusEnum;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\ShopOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDataBase;

    private function createProduct(): Product
    {
        $shop = Shop::create([
            'name' => 'Test',
            'seller_id' => Seller::factory()->create()->id,
            'description' => 'Test',
        ]);
        $category = Category::create([
            'name' => 'Test',
            'parent_id' => null,
        ]);

        return Product::create([
            'name' => 'Test',
            'category_id' => $category->id,
            'shop_id' => $shop->id,
            'quantity' => 100,
        ]);
    }

    private function createOrderWithStatusDelivered(User $user, Product $product, OrderStatusEnum $status): void
    {
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'address_id' => Address::factory()->create(['user_id' => $user->id]),
            'status' => $status->value,
            'total_price' => 100000,
        ]);
        $shop = Shop::create([
            'name' => 'Test',
            'seller_id' => Seller::factory()->create()->id,
            'description' => 'Test',
        ]);
        $shopOrder = ShopOrder::factory()->create([
            'order_id' => $order->id,
            'shop_id' => $shop->id,
            'status' => OrderStatusEnum::DELIVERED->value,
            'subtotal_price' => 100000,
        ]);
        $productVariant = ProductVariant::factory()->create([
            'access_status' => StatusEnum::ACCESS->value,
            'product_id' => $product->id,
        ]);
        OrderItem::factory()->create([
            'shop_order_id' => $shopOrder->id,
            'product_variant_id' => $productVariant->id,
        ]);
    }

    #[Test]
    public function guest_can_see_only_verified_reviews(): void
    {
        $product = $this->createProduct();
        Review::factory(5)
            ->create([
                'user_id' => fn () => User::factory()->create()->id,
                'product_id' => $product->id,
                'access_status' => StatusEnum::ACCESS->value,
            ]);
        Review::factory(10)->create([
            'user_id' => fn () => User::factory()->create()->id,
            'product_id' => $product->id,
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $request = $this->getJson("api/v1/products/{$product->uuid}/reviews");

        $request->assertStatus(200);
        $request->assertJsonCount(5, 'data');
    }

    #[Test]
    public function guest_cannot_see_unverified_reviews(): void
    {
        $product = $this->createProduct();
        $review = Review::factory()->create([
            'user_id' => User::factory()->create()->id,
            'product_id' => $product->id,
            'access_status' => StatusEnum::CHECKING->value,
        ]);

        $request = $this->getJson("api/v1/products/{$product->uuid}/reviews/{$review->uuid}");
        $request->assertStatus(422);
    }

    #[Test]
    public function admin_can_see_all_reviews(): void
    {
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
        ]);
        $product = $this->createProduct();
        $review = Review::factory()->create([
            'user_id' => User::factory()->create()->id,
            'product_id' => $product->id,
            'access_status' => StatusEnum::CHECKING->value,
        ]);

        $request = $this->actingAs($admin)->getJson("api/v1/products/{$product->uuid}/reviews/{$review->uuid}");
        $request->assertStatus(200);
    }

    #[Test]
    public function user_can_add_review(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $this->createOrderWithStatusDelivered($user, $product, OrderStatusEnum::DELIVERED);
        $request = $this->actingAs($user)->postJson("api/v1/products/{$product->uuid}/reviews", [
            'rating' => 5,
            'text' => 'TestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTest',
        ]);
        $request->assertStatus(201);
    }

    #[Test]
    public function user_cannot_add_second_review(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $this->createOrderWithStatusDelivered($user, $product, OrderStatusEnum::DELIVERED);
        Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'text' => 'TestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTest',
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $request = $this->actingAs($user)->postJson("api/v1/products/{$product->uuid}/reviews", [
            'rating' => 5,
            'text' => 'TestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTest',
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function user_cannot_add_review_if_order_not_delivered(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $this->createOrderWithStatusDelivered($user, $product, OrderStatusEnum::CREATED);
        Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'text' => 'TestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTest',
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $request = $this->actingAs($user)->postJson("api/v1/products/{$product->uuid}/reviews", [
            'rating' => 5,
            'text' => 'TestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTest',
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function user_can_update_his_review(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct();
        $this->createOrderWithStatusDelivered($user, $product, OrderStatusEnum::DELIVERED);
        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'text' => 'TestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTest',
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $request = $this->actingAs($user)->putJson("api/v1/products/{$product->uuid}/reviews/{$review->uuid}", [
            'rating' => 5,
            'text' => 'Test2TestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTest',
        ]);
        $request->assertStatus(200);
    }

    #[Test]
    public function user_cannot_update_not_his_review(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();
        $product = $this->createProduct();
        $this->createOrderWithStatusDelivered($user, $product, OrderStatusEnum::DELIVERED);
        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => 5,
            'text' => 'TestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTest',
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $request = $this->actingAs($anotherUser)->putJson("api/v1/products/{$product->uuid}/reviews/{$review->uuid}", [
            'rating' => 5,
            'text' => 'Test2TestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTestTest',
        ]);
        $request->assertStatus(403);
    }
}
