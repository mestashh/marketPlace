<?php

namespace Tests\Feature;

use App\Enums\StatusEnum;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SellerTest extends TestCase
{
    use RefreshDataBase;

    #[Test]
    public function guest_can_see_only_access_sellers(): void
    {
        Seller::factory()->create([
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        Seller::factory()->create([
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $response = $this->getJson('api/v1/sellers');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');
    }

    #[Test]
    public function admin_can_see_all_sellers(): void
    {
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
        ]);
        Seller::factory()->create([
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        Seller::factory()->create([
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $response = $this->actingAs($admin)->getJson('api/v1/sellers');
        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    #[Test]
    public function guest_cannot_see_banned_seller(): void
    {
        $seller = Seller::factory()->create([
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $response = $this->getJson("api/v1/sellers/{$seller->uuid}");
        $response->assertStatus(404);
    }

    #[Test]
    public function admin_can_see_banned_seller(): void
    {
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
        ]);
        $seller = Seller::factory()->create([
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $response = $this->actingAs($admin)->getJson("api/v1/sellers/{$seller->uuid}");
        $response->assertStatus(200);
    }

    #[Test]
    public function user_can_create_seller(): void
    {
        $user = User::factory()->create([
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $response = $this->actingAs($user)->postJson('api/v1/sellers', [
            'TIN' => '187069780',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('sellers', [
            'user_id' => $user->id,
            'TIN' => '187069780',
        ]);
    }

    #[Test]
    public function banned_user_cannot_create_seller(): void
    {
        $user = User::factory()->create([
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $response = $this->actingAs($user)->postJson('api/v1/sellers', [
            'TIN' => '187069780',
        ]);
        $response->assertStatus(403);
    }

    #[Test]
    public function seller_can_update_seller(): void
    {
        $user = User::factory()->create([
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $seller = Seller::factory()->create([
            'user_id' => $user->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);

        $response = $this->actingAs($user)->patchJson("api/v1/sellers/{$seller->uuid}", [
            'TIN' => '795388233',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('sellers', [
            'id' => $seller->id,
            'TIN' => '795388233',
        ]);
    }

    #[Test]
    public function seller_cannot_update_seller_with_status_banned(): void
    {
        $user = User::factory()->create([
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $seller = Seller::factory()->create([
            'access_status' => StatusEnum::BANNED->value,
        ]);
        $response = $this->actingAs($user)->patchJson("api/v1/sellers/{$seller->uuid}", [
            'TIN' => '795388233',
        ]);
        $response->assertStatus(403);
    }

    #[Test]
    public function admin_can_update_seller(): void
    {
        $userForSeller = User::Factory()->create();
        $userForAdmin = User::Factory()->create();
        $seller = Seller::Factory()->create([
            'user_id' => $userForSeller->id,
        ]);
        Admin::Factory()->create([
            'user_id' => $userForAdmin->id,
        ]);

        $response = $this->actingAs($userForAdmin)->patchJson("api/v1/sellers/{$seller->uuid}", [
            'TIN' => '795388233',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('sellers', [
            'id' => $seller->id,
            'TIN' => '795388233',
        ]);
    }

    #[Test]
    public function admin_can_update_seller_with_status_banned(): void
    {
        $userForSeller = User::Factory()->create();
        $userForAdmin = User::Factory()->create();
        $seller = Seller::Factory()->create([
            'user_id' => $userForSeller->id,
            'access_status' => StatusEnum::BANNED->value,
        ]);
        Admin::Factory()->create([
            'user_id' => $userForAdmin->id,
        ]);

        $response = $this->actingAs($userForAdmin)->patchJson("api/v1/sellers/{$seller->uuid}", [
            'TIN' => '795388233',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('sellers', [
            'id' => $seller->id,
            'TIN' => '795388233',
        ]);
    }
}
