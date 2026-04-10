<?php

namespace Tests\Feature;

use App\Enums\StatusEnum;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDataBase;

    #[Test]
    public function guest_can_see_all_access_shops(): void
    {
        Shop::factory(10)->create([
            'seller_id' => Seller::factory()->create()->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        Shop::factory(10)->create([
            'seller_id' => Seller::factory()->create()->id,
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $request = $this->getJson('api/v1/shops');
        $request->assertStatus(200);
        $request->assertJsonCount(10, 'data');
    }

    #[Test]
    public function guest_can_see_one_shop_with_status_access(): void
    {
        $shop = Shop::factory()->create([
            'seller_id' => Seller::factory()->create()->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $user = User::factory()->create();
        $request = $this->getJson("api/v1/shops/{$shop->uuid}");
        $request->assertStatus(200);
    }

    #[Test]
    public function guest_cannot_see_shop_with_status_not_access(): void
    {
        $shop = Shop::factory()->create([
            'seller_id' => Seller::factory()->create()->id,
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $user = User::factory()->create();
        $request = $this->actingAs($user)->getJson("api/v1/shops/{$shop->uuid}");
        $request->assertStatus(422);
    }

    #[Test]
    public function admin_can_see_all_shops(): void
    {
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
        ]);
        Shop::factory(10)->create([
            'seller_id' => Seller::factory()->create()->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        Shop::factory(10)->create([
            'seller_id' => Seller::factory()->create()->id,
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $request = $this->actingAs($admin)->getJson('api/v1/shops');
        $request->assertStatus(200);
        $request->assertJsonCount(20, 'data');
    }

    #[Test]
    public function user_cannot_make_shop(): void
    {
        $user = User::factory()->create();
        $request = $this->actingAs($user)->postJson('api/v1/shops', [
            'name' => 'Test',
            'description' => 'Test',
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function seller_can_make_shop(): void
    {
        $seller = User::factory()->create();
        Seller::factory()->create([
            'user_id' => $seller->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $request = $this->actingAs($seller)->postJson('api/v1/shops', [
            'name' => 'Test',
            'description' => 'Test',
        ]);

        $request->assertStatus(201);

        $this->assertDatabaseHas('shops', [
            'name' => 'Test',
            'description' => 'Test',
        ]);
    }

    #[Test]
    public function seller_cannot_make_second_shop(): void
    {
        $seller = User::factory()->create();
        Seller::factory()->create([
            'user_id' => $seller->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        Shop::factory()->create([
            'seller_id' => $seller->seller->id,
        ]);
        $request = $this->actingAs($seller)->postJson('api/v1/shops', [
            'name' => 'Test',
            'description' => 'Test',
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function seller_can_change_his_shop(): void
    {
        $seller = User::factory()->create();
        Seller::factory()->create([
            'user_id' => $seller->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $shop = Shop::factory()->create([
            'seller_id' => $seller->seller->id,
            'description' => 'Test',
        ]);
        $request = $this->actingAs($seller)->putJson("api/v1/shops/{$shop->uuid}", [
            'name' => 'Test',
            'description' => 'Test2',
        ]);

        $request->assertStatus(200);
        $this->assertDatabaseHas('shops', [
            'name' => 'Test',
            'description' => 'Test2',
        ]);
    }

    #[Test]
    public function seller_cannot_change_not_his_shop(): void
    {
        $seller = User::factory()->create();
        Seller::factory()->create([
            'user_id' => $seller->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $anotherSeller = User::factory()->create();
        Seller::factory()->create([
            'user_id' => $anotherSeller->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $shop = Shop::factory()->create([
            'seller_id' => $seller->seller->id,
            'description' => 'Test',
        ]);
        $request = $this->actingAs($anotherSeller)->putJson("api/v1/shops/{$shop->uuid}", [
            'name' => 'Test',
            'description' => 'Test2',
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function user_cannot_change_any_shop(): void
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        Seller::factory()->create([
            'user_id' => $seller->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $shop = Shop::factory()->create([
            'seller_id' => $seller->seller->id,
            'description' => 'Test',
        ]);
        $request = $this->actingAs($user)->putJson("api/v1/shops/{$shop->uuid}", [
            'name' => 'Test3',
            'description' => 'Test3',
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function admin_can_change_any_shop(): void
    {
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
        ]);
        $seller = User::factory()->create();
        Seller::factory()->create([
            'user_id' => $seller->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $shop = Shop::factory()->create([
            'seller_id' => $seller->seller->id,
            'description' => 'Test',
        ]);
        $request = $this->actingAs($admin)->putJson("api/v1/shops/{$shop->uuid}", [
            'name' => 'Test3',
            'description' => 'Test3',
        ]);
        $request->assertStatus(200);
    }
}
