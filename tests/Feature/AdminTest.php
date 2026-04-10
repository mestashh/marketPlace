<?php

namespace Tests\Feature;

use App\Enums\AdminRoleEnum;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDataBase;

    #[Test]
    public function guest_cannot_see_all_admins(): void
    {
        $response = $this->getJson('api/v1/admins/');

        $response->assertStatus(401);
    }

    #[Test]
    public function guest_cannot_see_admin(): void
    {
        $admin = Admin::factory()->create();
        $response = $this->getJson("api/v1/admins/{$admin->uuid}");

        $response->assertStatus(401);
    }

    #[Test]
    public function user_cannot_see_all_admins(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson('api/v1/admins/');

        $response->assertStatus(403);
    }

    #[Test]
    public function user_cannot_see_admin(): void
    {
        $admin = Admin::factory()->create();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->getJson("api/v1/admins/{$admin->uuid}");

        $response->assertStatus(403);
    }

    #[Test]
    public function super_admin_can_see_all_admins(): void
    {
        Admin::factory(10)->create();
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);
        $response = $this->actingAs($admin)->getJson('api/v1/admins/');

        $response->assertStatus(200);
        $response->assertJsonCount(11, 'data');
    }

    #[Test]
    public function super_admin_can_see_admin(): void
    {
        $anotherAdmin = Admin::factory()->create();
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);
        $response = $this->actingAs($admin)->getJson("api/v1/admins/{$anotherAdmin->uuid}");

        $response->assertStatus(200);
    }

    #[Test]
    public function guest_cannot_create_admin(): void
    {
        $response = $this->postJson('api/v1/admins', [
            'user_id' => 1,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $response->assertStatus(401);
    }

    #[Test]
    public function user_cannot_create_admin(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('api/v1/admins', [
            'user_id' => $user->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $response->assertStatus(403);
    }

    #[Test]
    public function support_admin_cannot_create_admin(): void
    {
        $user = User::factory()->create();
        $support = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $support->id,
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);
        $response = $this->actingAs($support)->postJson('api/v1/admins', [
            'user_id' => $user->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $response->assertStatus(403);
    }

    #[Test]
    public function admin_admin_cannot_create_admin(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $response = $this->actingAs($admin)->postJson('api/v1/admins', [
            'user_id' => $user->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $response->assertStatus(403);
    }

    #[Test]
    public function super_admin_can_create_admin(): void
    {
        $user = User::factory()->create();
        $super_admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $super_admin->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);
        $response = $this->actingAs($super_admin)->postJson('api/v1/admins', [
            'user_id' => $user->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('admins', [
            'user_id' => $user->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
    }

    #[Test]
    public function guest_cannot_update_admin(): void
    {
        $user = User::factory()->create();
        $admin = Admin::factory()->create();
        $response = $this->putJson("api/v1/admins/{$admin->uuid}", [
            'user_id' => $user->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);

        $response->assertStatus(401);
    }

    #[Test]
    public function user_cannot_update_admin(): void
    {
        $user = User::factory()->create();
        $admin = Admin::factory()->create();
        $response = $this->actingAs($user)->putJson("api/v1/admins/{$admin->uuid}", [
            'user_id' => $user->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function support_cannot_update_admin(): void
    {
        $user = User::factory()->create();
        $support = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $support->id,
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);
        $admin = Admin::factory()->create();
        $response = $this->actingAs($support)->putJson("api/v1/admins/{$admin->uuid}", [
            'user_id' => $user->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function admin_cannot_update_admin(): void
    {
        $user = User::factory()->create();
        $anotherAdmin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $anotherAdmin->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $admin = Admin::factory()->create();
        $response = $this->actingAs($anotherAdmin)->putJson("api/v1/admins/{$admin->uuid}", [
            'user_id' => $user->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);

        $response->assertStatus(403);
    }

    #[Test]
    public function super_admin_can_update_admin(): void
    {
        $user = User::factory()->create();
        $superAdmin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $superAdmin->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);
        $admin = Admin::factory()->create([
            'user_id' => $user->id,
        ]);
        $response = $this->actingAs($superAdmin)->putJson("api/v1/admins/{$admin->uuid}", [
            'user_id' => $user->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('admins', [
            'user_id' => $user->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);
    }

    #[Test]
    public function guest_cannot_delete_admin(): void
    {
        $admin = Admin::factory()->create();

        $request = $this->deleteJson("api/v1/admins/{$admin->uuid}");

        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_delete_admin(): void
    {
        $admin = Admin::factory()->create();

        $request = $this->deleteJson("api/v1/admins/{$admin->uuid}");

        $request->assertStatus(401);
    }

    #[Test]
    public function support_cannot_delete_admin(): void
    {
        $admin = Admin::factory()->create();
        $support = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $support->id,
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);

        $request = $this->actingAs($support)->deleteJson("api/v1/admins/{$admin->uuid}");

        $request->assertStatus(403);
    }

    #[Test]
    public function admin_cannot_delete_admin(): void
    {
        $admin = Admin::factory()->create();
        $support = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $support->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);

        $request = $this->actingAs($support)->deleteJson("api/v1/admins/{$admin->uuid}");

        $request->assertStatus(403);
    }

    #[Test]
    public function super_admin_can_delete_admin(): void
    {
        $admin = Admin::factory()->create();
        $support = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $support->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);

        $request = $this->actingAs($support)->deleteJson("api/v1/admins/{$admin->uuid}");

        $request->assertStatus(204);
    }
}
