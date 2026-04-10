<?php

namespace Tests\Feature;

use App\Enums\AdminRoleEnum;
use App\Models\Address;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDataBase;

    #[Test]
    public function guest_cannot_see_any_address(): void
    {
        Address::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $request = $this->getJson('api/v1/addresses');

        $request->assertStatus(401);
    }

    #[Test]
    public function user_can_see_his_addresses(): void
    {
        $user = User::factory()->create();
        Address::factory(2)->create([
            'user_id' => $user,
        ]);
        Address::factory(5)->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $request = $this->actingAs($user)->getJson('api/v1/addresses');
        $request->assertStatus(200);
        $request->assertJsonCount(2, 'data');
    }

    #[Test]
    public function user_cannot_see_not_his_addresses(): void
    {
        $user = User::factory()->create();
        Address::factory(2)->create([
            'user_id' => $user,
        ]);
        $anotherAddress = Address::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        $request = $this->actingAs($user)->getJson("api/v1/addresses/{$anotherAddress->uuid}");
        $request->assertStatus(403);
    }

    #[Test]
    public function admin_can_see_all_addresses(): void
    {
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);
        Address::factory(10)->create([
            'user_id' => User::factory()->create(),
        ]);

        $request = $this->actingAs($admin)->getJson('api/v1/addresses');
        $request->assertStatus(200);
        $request->assertJsonCount(10, 'data');
    }

    #[Test]
    public function user_can_add_address(): void
    {
        $user = User::factory()->create();
        $request = $this->ActingAs($user)->postJson('api/v1/addresses', [
            'user_id' => $user->id,
            'country' => 'Country',
            'city' => 'City',
            'street' => 'Street',
            'house' => 'House',
            'phone' => 'Phone',
            'description' => 'Description',
        ]);

        $request->assertStatus(201);
    }

    #[Test]
    public function user_can_update_his_address(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);
        $request = $this->ActingAs($user)->putJson("api/v1/addresses/{$address->uuid}", [
            'Description' => 'New Description',
        ]);

        $request->assertStatus(200);
    }

    #[Test]
    public function user_cannot_update_not_his_address(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create([
            'user_id' => User::factory()->create(),
        ]);
        $request = $this->ActingAs($user)->putJson("api/v1/addresses/{$address->uuid}", [
            'Description' => 'New Description',
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function admin_can_update_any_address(): void
    {
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
        ]);
        $address = Address::factory()->create([
            'user_id' => User::factory()->create(),
        ]);
        $request = $this->ActingAs($admin)->putJson("api/v1/addresses/{$address->uuid}", [
            'Description' => 'New Description',
        ]);

        $request->assertStatus(200);
    }

    #[Test]
    public function admin_can_delete_any_address(): void
    {
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
        ]);
        $address = Address::factory()->create([
            'user_id' => User::factory()->create(),
        ]);
        $request = $this->ActingAs($admin)->deleteJson("api/v1/addresses/{$address->uuid}");

        $request->assertStatus(204);
    }
    #[Test]
    public function user_can_delete_his_address(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);
        $request = $this->ActingAs($user)->deleteJson("api/v1/addresses/{$address->uuid}");

        $request->assertStatus(204);
    }

    #[Test]
    public function user_cannot_delete_not_his_address(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create([
            'user_id' => User::factory()->create(),
        ]);
        $request = $this->ActingAs($user)->deleteJson("api/v1/addresses/{$address->uuid}");

        $request->assertStatus(403);
    }
}
