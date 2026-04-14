<?php

namespace Tests\Feature;

use App\Enums\AdminRoleEnum;
use App\Enums\StatusEnum;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductVariantTest extends TestCase
{
    use RefreshDataBase;

    private function createProductVariant(StatusEnum $status): array
    {
        $user = User::factory()->create();

        $seller = Seller::factory()->create([
            'user_id' => $user->id,
        ]);

        $shop = Shop::factory()->create([
            'seller_id' => $seller->id,
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

        $productVariant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'access_status' => $status->value,
        ]);

        return [$product, $seller, $productVariant];
    }

    #[Test]
    public function guest_can_see_product_variants_with_status_access(): void
    {
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->getJson("api/v1/products/{$arr[0]->uuid}/variants/");
        $request->assertStatus(200);
    }

    #[Test]
    public function guest_can_see_product_variant_with_status_access(): void
    {
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->getJson("api/v1/products/{$arr[0]->uuid}/variants/{$arr[2]->uuid}");
        $request->assertStatus(200);
    }

    #[Test]
    public function guest_cannot_see_product_variants_with_status_not_access(): void
    {
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        ProductVariant::factory()->create([
            'product_id' => $arr[0]->id,
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $request = $this->getJson("api/v1/products/{$arr[0]->uuid}/variants/");
        $request->assertStatus(200);
        $request->assertJsonCount(1, 'data');
    }

    #[Test]
    public function owner_can_see_product_variants_with_status_not_access(): void
    {
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        ProductVariant::factory()->create([
            'product_id' => $arr[0]->id,
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $request = $this->actingAs($arr[1]->user)->getJson("api/v1/products/{$arr[0]->uuid}/variants/");
        $request->assertStatus(200);
        $request->assertJsonCount(2, 'data');
    }

    #[Test]
    public function admin_can_see_product_variants_with_status_not_access(): void
    {
        $support = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        ProductVariant::factory()->create([
            'product_id' => $arr[0]->id,
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $request = $this->actingAs($support->user)->getJson("api/v1/products/{$arr[0]->uuid}/variants/");
        $request->assertStatus(200);
        $request->assertJsonCount(2, 'data');
    }

    #[Test]
    public function guest_cannot_create_product_variant(): void
    {
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->postJson("api/v1/products/{$arr[0]->uuid}/variants", [
            'name' => 'Test',
            'description' => 'Test',
            'stock' => 1000,
            'sku' => 'SKU-151515',
            'price' => 999,
        ]);
        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_create_product_variant(): void
    {
        $user = User::factory()->create();
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->actingAs($user)->postJson("api/v1/products/{$arr[0]->uuid}/variants", [
            'name' => 'Test',
            'description' => 'Test',
            'stock' => 1000,
            'sku' => 'SKU-151515',
            'price' => 999,
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function seller_can_create_product_variant(): void
    {
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->actingAs($arr[1]->user)->postJson("api/v1/products/{$arr[0]->uuid}/variants", [
            'name' => 'Test',
            'description' => 'Test',
            'stock' => 1000,
            'sku' => 'SKU-151515',
            'price' => 999,
        ]);
        $request->assertStatus(201);
        $this->assertDatabaseHas('product_variants', [
            'name' => 'Test',
            'description' => 'Test',
            'stock' => 1000,
            'sku' => 'SKU-151515',
            'price' => 999,
        ]);
    }

    #[Test]
    public function guest_cannot_update_any_product_variant(): void
    {
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->putJson("api/v1/products/{$arr[0]->uuid}/variants/{$arr[2]->uuid}", [
            'name' => 'Test',
            'price' => 123,
        ]);

        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_update_any_product_variant(): void
    {
        $user = User::factory()->create();
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->actingAs($user)->putJson("api/v1/products/{$arr[0]->uuid}/variants/{$arr[2]->uuid}", [
            'name' => 'Test',
            'price' => 123,
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function seller_cannot_update_not_his_product_variant(): void
    {
        $seller = Seller::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->actingAs($seller->user)->putJson("api/v1/products/{$arr[0]->uuid}/variants/{$arr[2]->uuid}", [
            'name' => 'Test',
            'price' => 123,
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function seller_can_update_his_product_variant(): void
    {
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->actingAs($arr[1]->user)->putJson("api/v1/products/{$arr[0]->uuid}/variants/{$arr[2]->uuid}", [
            'name' => 'Test',
            'price' => 123,
        ]);

        $request->assertStatus(200);
    }

    #[Test]
    public function support_cannot_update_any_product_variant(): void
    {
        $support = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->actingAs($support->user)->putJson("api/v1/products/{$arr[0]->uuid}/variants/{$arr[2]->uuid}", [
            'name' => 'Test',
            'price' => 123,
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function admin_cannot_update_any_product_variant(): void
    {
        $admin = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->actingAs($admin->user)->putJson("api/v1/products/{$arr[0]->uuid}/variants/{$arr[2]->uuid}", [
            'name' => 'Test',
            'price' => 123,
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function super_admin_can_update_any_product_variant(): void
    {
        $superAdmin = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);
        $arr = $this->createProductVariant(StatusEnum::ACCESS);
        $request = $this->actingAs($superAdmin->user)->putJson("api/v1/products/{$arr[0]->uuid}/variants/{$arr[2]->uuid}", [
            'name' => 'Test',
            'price' => 123,
        ]);

        $request->assertStatus(200);
    }

}
