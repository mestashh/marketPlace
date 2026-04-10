<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_see_his_profile(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson("api/v1/users/{$user->uuid}");
        $response->assertStatus(200);
    }

    #[Test]
    public function user_cannot_see_not_his_profile(): void
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user)->getJson("api/v1/users/{$user2->uuid}");
        $response->assertStatus(403);
    }

    #[Test]
    public function user_cannot_see_all_profiles(): void
    {
        User::factory(10)->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/v1/users');
        $response->assertStatus(403);
    }

    #[Test]
    public function guest_cannot_see_all_profiles(): void
    {
        User::factory(10)->create();
        $response = $this->getJson('api/v1/users');
        $response->assertStatus(401);
    }

    #[Test]
    public function admin_can_see_user_profile(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->getJson("api/v1/users/{$user->uuid}");
        $response->assertStatus(200);
    }

    #[Test]
    public function admin_can_see_all_profiles(): void
    {
        User::factory(10)->create();
        $user = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->getJson('api/v1/users');
        $response->assertStatus(200);
        $response->assertJsonCount(11, 'data');
    }

    #[Test]
    public function user_can_create_account(): void
    {
        $response = $this->postJson('api/v1/auth/register', [
            'last_name' => 'Pevsov',
            'first_name' => 'Dmitry',
            'email' => 'testtest@example.com',
            'phone' => '88005553535',
            'password' => 'password123',
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'testtest@example.com',
        ]);
    }

    #[Test]
    public function user_can_update_his_account(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->putJson("api/v1/users/{$user->uuid}", [
            'first_name' => 'TestTest',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'first_name' => 'TestTest',
        ]);
    }

    #[Test]
    public function user_cannot_update_another_account(): void
    {
        $user = User::factory()->create();
        $anotherUser = User::factory()->create();

        $response = $this->actingAs($user)->putJson("api/v1/users/{$anotherUser->uuid}", [
            'first_name' => 'TestTest',
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function admin_can_update_any_account(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();
        Admin::Factory()->create([
            'user_id' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->putJson("api/v1/users/{$user->uuid}", [
            'first_name' => 'TestTest',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'first_name' => 'TestTest',
        ]);
    }
    // дописать тест email
}
