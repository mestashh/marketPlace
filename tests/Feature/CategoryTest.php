<?php

namespace Tests\Feature;

use App\Enums\AdminRoleEnum;
use App\Models\Admin;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDataBase;

    private function makeCategories(): \Illuminate\Database\Eloquent\Builder
    {
        Category::create([
            'parent_id' => null,
            'name' => 'Test',
        ]);
        Category::create([
            'parent_id' => null,
            'name' => 'Test2',
        ]);
        Category::create([
            'parent_id' => null,
            'name' => 'Test3',
        ]);

        return Category::query();
    }

    #[Test]
    public function guest_can_see_all_categories(): void
    {
        $this->makeCategories();
        $request = $this->getJson('api/v1/categories');
        $request->assertStatus(200);
        $request->assertJsonCount(3, 'data');
    }

    #[Test]
    public function guest_cannot_change_categories(): void
    {
        $category = Category::create([
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request = $this->putJson("api/v1/categories/{$category->uuid}", [
            'name' => 'Test2',
        ]);

        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_change_categories(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request = $this->actingAs($user)->putJson("api/v1/categories/{$category->uuid}", [
            'name' => 'Test2',
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function support_cannot_change_categories(): void
    {
        $support = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $support->id,
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);

        $category = Category::create([
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request = $this->actingAs($support)->putJson("api/v1/categories/{$category->uuid}", [
            'name' => 'Test2',
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function admin_can_change_categories(): void
    {
        $admin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $admin->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);

        $category = Category::create([
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request = $this->actingAs($admin)->putJson("api/v1/categories/{$category->uuid}", [
            'name' => 'Test2',
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function super_admin_can_change_category(): void
    {
        $superAdmin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $superAdmin->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);

        $category = Category::create([
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request = $this->actingAs($superAdmin)->putJson("api/v1/categories/{$category->uuid}", [
            'name' => 'Test2',
        ]);

        $request->assertStatus(200);
        $this->assertDatabaseHas('categories', [
            'name' => 'Test2',
        ]);
    }

    #[Test]
    public function super_admin_cannot_update_category_if_name_already_exists(): void
    {
        $superAdmin = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $superAdmin->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);

        $category = Category::create([
            'parent_id' => null,
            'name' => 'Test',
        ]);
        Category::create([
            'parent_id' => null,
            'name' => 'Test2',
        ]);
        $request = $this->actingAs($superAdmin)->putJson("api/v1/categories/{$category->uuid}", [
            'name' => 'Test2',
        ]);

        $request->assertStatus(422);
    }

    #[Test]
    public function guest_cannot_create_category(): void
    {
        $request = $this->postJson('api/v1/categories', [
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_create_category(): void
    {
        $user = User::factory()->create();
        $request = $this->actingAs($user)->postJson('api/v1/categories', [
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function support_cannot_create_category(): void
    {
        $user = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $user->id,
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);
        $request = $this->actingAs($user)->postJson('api/v1/categories', [
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function admin_cannot_create_category(): void
    {
        $user = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $user->id,
            'role' => AdminRoleEnum::ADMIN->value,
        ]);
        $request = $this->actingAs($user)->postJson('api/v1/categories', [
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request->assertStatus(403);
    }

    #[Test]
    public function super_admin_can_create_category(): void
    {
        $user = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $user->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);
        $request = $this->actingAs($user)->postJson('api/v1/categories', [
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request->assertStatus(201);
        $this->assertDatabaseHas('categories', [
            'name' => 'Test',
        ]);
    }

    #[Test]
    public function super_admin_cannot_create_second_category_with_same_name(): void
    {
        $user = User::factory()->create();
        Admin::factory()->create([
            'user_id' => $user->id,
            'role' => AdminRoleEnum::SUPER_ADMIN->value,
        ]);
        Category::create([
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request = $this->actingAs($user)->postJson('api/v1/categories', [
            'parent_id' => null,
            'name' => 'Test',
        ]);
        $request->assertStatus(422);
    }
}
