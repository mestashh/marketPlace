<?php

namespace Tests\Feature;

use App\Enums\AdminRoleEnum;
use App\Enums\StatusEnum;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDataBase;

    private function createProduct(StatusEnum $status): Product
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
            'access_status' => $status->value,
        ]);
    }

    private function createSellerWithShopAndProduct(StatusEnum $status): array
    {
        $seller = Seller::factory()->create([
            'user_id' => User::factory()->create()->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
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

        return [
            $seller,
            $shop,
            $product,
        ];
    }

    #[Test]
    public function guest_can_see_only_access_products(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->createProduct(StatusEnum::ACCESS);
            $this->createProduct(StatusEnum::BANNED);
        }
        $request = $this->getJson('api/v1/products');
        $request->assertStatus(200);
        $request->assertjsonCount(10, 'data');
    }

    #[Test]
    public function admin_can_see_all_products(): void
    {
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
        ]);

        for ($i = 0; $i < 10; $i++) {
            $this->createProduct(StatusEnum::ACCESS);
            $this->createProduct(StatusEnum::BANNED);
        }
        $request = $this->actingAs($admin)->getJson('api/v1/products');
        $request->assertStatus(200);
        $request->assertjsonCount(20, 'data');
    }

    #[Test]
    public function guest_cannot_add_product(): void
    {
        $seller = Seller::factory()->create([
            'user_id' => User::factory()->create()->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        Shop::factory()->create([
            'seller_id' => $seller->id,
            'name' => 'Test',
            'description' => 'TestTestTestTestTestTestTestTestTestTestTest',
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $category = Category::create([
            'name' => 'Test',
            'parent_id' => null,
        ]);
        $request = $this->postJson('api/v1/products', [
            'shop_id' => $seller->shop->id,
            'category_id' => $category->id,
            'name' => 'Test',
            'quantity' => 1000,
            'description' => 'TestTestTestTestTestTestTestTestTestTestTest',
        ]);

        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_add_product(): void
    {
        $user = User::factory()->create();
        $seller = Seller::factory()->create([
            'user_id' => User::factory()->create()->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        Shop::factory()->create([
            'seller_id' => $seller->id,
            'name' => 'Test',
            'description' => 'TestTestTestTestTestTestTestTestTestTestTest',
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $category = Category::create([
            'name' => 'Test',
            'parent_id' => null,
        ]);
        $request = $this->actingAs($user)->postJson('api/v1/products', [
            'shop_id' => $seller->shop->id,
            'category_id' => $category->id,
            'name' => 'Test',
            'quantity' => 1000,
            'description' => 'TestTestTestTestTestTestTestTestTestTestTest',
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function seller_can_add_product(): void
    {
        $seller = Seller::factory()->create([
            'user_id' => User::factory()->create()->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        Shop::factory()->create([
            'seller_id' => $seller->id,
            'name' => 'Test',
            'description' => 'TestTestTestTestTestTestTestTestTestTestTest',
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $category = Category::create([
            'name' => 'Test',
            'parent_id' => null,
        ]);
        $request = $this->actingAs($seller->user)->postJson('api/v1/products', [
            'shop_id' => $seller->shop->id,
            'category_id' => $category->id,
            'name' => 'Test',
            'quantity' => 1000,
            'description' => 'TestTestTestTestTestTestTestTestTestTestTest',
        ]);

        $request->assertStatus(201);
        $this->assertDataBaseHas('products', [
            'name' => 'Test',
            'quantity' => 1000,
            'description' => 'TestTestTestTestTestTestTestTestTestTestTest',
            'access_status' => StatusEnum::CHECKING->value,
        ]);
    }

    #[Test]
    public function guest_cannot_change_product(): void
    {
        $product = $this->createProduct(StatusEnum::ACCESS);
        $request = $this->putJson("api/v1/products/$product->uuid");
        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_change_product(): void
    {
        $user = User::factory()->create();
        $product = $this->createProduct(StatusEnum::ACCESS);
        $request = $this->actingAs($user)->putJson("api/v1/products/$product->uuid");
        $request->assertStatus(403);
    }

    #[Test]
    public function seller_can_change_his_product(): void
    {
        $arr = $this->createSellerWithShopAndProduct(StatusEnum::ACCESS);
        $product = $arr[2];
        $request = $this->actingAs($arr[0]->user)->putJson("api/v1/products/$product->uuid", [
            'name' => 'Test2',
        ]);
        $request->assertStatus(200);
        $this->assertDataBaseHas('products', [
            'name' => 'Test2',
            'quantity' => 100,
        ]);
    }

    #[Test]
    public function seller_cannot_change_not_his_product(): void
    {
        $seller = Seller::factory()->create();
        $arr = $this->createSellerWithShopAndProduct(StatusEnum::ACCESS);
        $product = $arr[2];
        $request = $this->actingAs($seller->user)->putJson("api/v1/products/$product->uuid", [
            'name' => 'Test2',
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function support_cannot_change_product(): void
    {
        $support = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);
        $arr = $this->createSellerWithShopAndProduct(StatusEnum::ACCESS);
        $product = $arr[2]->uuid;
        $request = $this->actingAs($support->user)->putJson("api/v1/products/$product", [
            'name' => 'Test2',
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function admin_cannot_change_product(): void
    {
        $admin = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $arr = $this->createSellerWithShopAndProduct(StatusEnum::ACCESS);
        $product = $arr[2]->uuid;
        $request = $this->actingAs($admin->user)->putJson("api/v1/products/$product", [
            'name' => 'Test2',
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function super_admin_can_change_product(): void
    {
        $superAdmin = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);
        $arr = $this->createSellerWithShopAndProduct(StatusEnum::ACCESS);
        $product = $arr[2]->uuid;
        $request = $this->actingAs($superAdmin->user)->putJson("api/v1/products/$product", [
            'name' => 'Test2',
        ]);

        $request->assertStatus(200);

        $this->assertDataBaseHas('products', [
            'name' => 'Test2',
        ]);
    }


}
