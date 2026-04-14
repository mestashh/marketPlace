<?php

namespace Tests\Feature;

use App\Enums\AdminRoleEnum;
use App\Enums\StatusEnum;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDataBase;

    private function createProductImage(StatusEnum $status): array
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

        $productImage = ProductImage::factory()->create([
            'product_id' => $product->id,
            'access_status' => $status->value,
            'is_main' => true,
        ]);

        return [$product, $seller, $productImage];
    }

    #[Test]
    public function guest_can_see_product_images_with_status_access(): void
    {
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->getJson("api/v1/products/{$arr[0]->uuid}/images");
        $request->assertStatus(200);
        $request->assertJsonCount(1, 'data');
    }

    #[Test]
    public function guest_can_see_product_image_with_status_access(): void
    {
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->getJson("api/v1/products/{$arr[0]->uuid}/images/{$arr[2]->uuid}");
        $request->assertStatus(200);
    }

    #[Test]
    public function guest_cannot_see_product_images_with_status_not_access(): void
    {
        $arr = $this->createProductImage(StatusEnum::BANNED);
        $request = $this->getJson("api/v1/products/{$arr[0]->uuid}/images");
        $request->assertStatus(200);
        $request->assertJsonCount(0, 'data');
    }

    #[Test]
    public function guest_cannot_add_product_not_main_image(): void
    {
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->postJson("api/v1/products/{$arr[0]->uuid}/images", [
            'path' => 'Test.Test',
            'is_main' => false,
            'position' => 1,
        ]);
        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_add_product_not_main_image(): void
    {
        $user = User::factory()->create();
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($user)->postJson("api/v1/products/{$arr[0]->uuid}/images", [
            'path' => 'Test.Test',
            'is_main' => false,
            'position' => 1,
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function seller_can_add_product_not_main_image(): void
    {
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($arr[1]->user)->postJson("api/v1/products/{$arr[0]->uuid}/images", [
            'path' => 'Test.Test',
            'is_main' => false,
            'position' => 1,
        ]);
        $request->assertStatus(201);
        $this->assertDatabaseHas('product_images', [
            'path' => 'Test.Test',
            'is_main' => false,
            'position' => 1,
        ]);
    }

    #[Test]
    public function seller_can_add_product_not_main_image_if_images_less_then_5(): void
    {
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        ProductImage::create([
            'is_main' => false,
            'path' => 'Test2est',
            'product_id' => $arr[0]->id,
            'position' => 1,
        ]);
        $request = $this->actingAs($arr[1]->user)->postJson("api/v1/products/{$arr[0]->uuid}/images", [
            'path' => 'Test.Test2',
            'is_main' => false,
            'position' => 1,
        ]);
        $request->assertStatus(201);
        $this->assertDatabaseHas('product_images', [
            'path' => 'Test.Test2',
            'is_main' => false,
            'position' => 2,
        ]);
    }

    #[Test]
    public function seller_cannot_add_product_not_main_image_if_images_more_than_5(): void
    {
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        for ($i = 0; $i <= 5; $i++) {
            ProductImage::factory()->create([
                'product_id' => $arr[0]->id,
                'position' => $i,
                'is_main' => false,
            ]);
        }
        $request = $this->actingAs($arr[1]->user)->postJson("api/v1/products/{$arr[0]->uuid}/images", [
            'path' => 'Test.Test2',
            'is_main' => false,
            'position' => 1,
        ]);
        $request->assertStatus(422);
    }

    #[Test]
    public function another_seller_cannot_add_product_not_main_image(): void
    {
        $seller = Seller::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($seller->user)->postJson("api/v1/products/{$arr[0]->uuid}/images", [
            'path' => 'Test.Test2',
            'is_main' => false,
            'position' => 1,
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function support_cannot_add_product_not_main_image(): void
    {
        $support = Admin::factory()->create([
            'user_id' => User::factory()->create(),
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($support->user)->postJson("api/v1/products/{$arr[0]->uuid}/images", [
            'path' => 'Test.Test',
            'is_main' => false,
            'position' => 1,
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function admin_cannot_add_product_not_main_image(): void
    {
        $admin = Admin::factory()->create([
            'user_id' => User::factory()->create(),
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($admin->user)->postJson("api/v1/products/{$arr[0]->uuid}/images", [
            'path' => 'Test.Test',
            'is_main' => false,
            'position' => 1,
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function super_admin_cannot_add_product_not_main_image(): void
    {
        $superAdmin = Admin::factory()->create([
            'user_id' => User::factory()->create(),
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($superAdmin->user)->postJson("api/v1/products/{$arr[0]->uuid}/images", [
            'path' => 'Test.Test',
            'is_main' => false,
            'position' => 1,
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function seller_can_add_main_image_if_not_exists(): void
    {
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($arr[1]->user)->postJson("api/v1/products/{$arr[0]->uuid}/images/", [
            'path' => 'Test.Test',
            'is_main' => true,
            'position' => 1,
        ]);

        $request->assertStatus(201);
        $this->assertDatabaseHas('product_images', [
            'path' => 'Test.Test',
            'is_main' => true,
            'position' => 0,
        ]);
    }

    #[Test]
    public function seller_can_update_main_image(): void
    {
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        ProductImage::factory()->create([
            'product_id' => $arr[0]->id,
            'path' => 'Test2',
            'is_main' => true,
            'position' => 0,
        ]);
        $request = $this->actingAs($arr[1]->user)->postJson("api/v1/products/{$arr[0]->uuid}/images/", [
            'path' => 'Test.Test',
            'is_main' => true,
            'position' => 1,
        ]);

        $request->assertStatus(201);
        $this->assertDatabaseHas('product_images', [
            'path' => 'Test.Test',
            'is_main' => true,
            'position' => 0,
        ]);
    }

    #[Test]
    public function seller_can_update_not_main_image(): void
    {
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($arr[1]->user)->putJson("api/v1/products/{$arr[0]->uuid}/images/{$arr[2]->uuid}", [
            'path' => 'Test2.Test',
            'is_main' => false,
            'position' => 5,
        ]);

        $request->assertStatus(200);
        $this->assertDataBaseHas('product_images', [
            'path' => 'Test2.Test',
            'is_main' => false,
            'position' => 5,
        ]);
    }

    #[Test]
    public function guest_cannot_delete_product_image(): void
    {
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->deleteJson("api/v1/products/{$arr[0]->uuid}/images/{$arr[2]->uuid}");
        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_delete_product_image(): void
    {
        $user = User::factory()->create();
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($user)->deleteJson("api/v1/products/{$arr[0]->uuid}/images/{$arr[2]->uuid}");
        $request->assertStatus(403);
    }

    #[Test]
    public function seller_can_delete_product_image(): void
    {
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($arr[1]->user)->deleteJson("api/v1/products/{$arr[0]->uuid}/images/{$arr[2]->uuid}");
        $request->assertStatus(204);
    }
    #[Test]
    public function support_cannot_delete_product_image(): void
    {
        $support = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($support->user)->deleteJson("api/v1/products/{$arr[0]->uuid}/images/{$arr[2]->uuid}");
        $request->assertStatus(403);
    }

    #[Test]
    public function admin_cannot_delete_product_image(): void
    {
        $admin = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($admin->user)->deleteJson("api/v1/products/{$arr[0]->uuid}/images/{$arr[2]->uuid}");
        $request->assertStatus(403);
    }

    #[Test]
    public function super_admin_can_delete_product_image(): void
    {
        $superAdmin = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);
        $arr = $this->createProductImage(StatusEnum::ACCESS);
        $request = $this->actingAs($superAdmin->user)->deleteJson("api/v1/products/{$arr[0]->uuid}/images/{$arr[2]->uuid}");
        $request->assertStatus(204);
    }
}
