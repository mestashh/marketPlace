<?php

namespace Tests\Feature;

use App\Enums\OrderStatusEnum;
use App\Enums\StatusEnum;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\ShopOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use refreshDataBase;

    private function createUserAndOrder(): array
    {
        $user = User::factory()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);
        $seller = Seller::factory()->create([
            'user_id' => User::factory()->create()->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $shop = Shop::factory()->create([
            'seller_id' => $seller->id,
            'access_status' => StatusEnum::ACCESS->value,
        ]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'address_id' => $address->id,
            'status' => OrderStatusEnum::DELIVERED->value,
        ]);
        $shopOrder = ShopOrder::factory()->create([
            'order_id' => $order->id,
            'shop_id' => $shop->id,
            'status' => OrderStatusEnum::DELIVERED->value,
        ]);

        return [$user, $seller, $order, $shopOrder];
    }

    #[Test]
    public function guest_cannot_see_any_orders(): void
    {
        $request = $this->getJson('api/v1/orders');
        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_see_not_his_orders(): void
    {
        $arr = $this->createUserAndOrder();
        $user = User::factory()->create();
        $request = $this->actingAs($user)->getJson("api/v1/orders/{$arr[2]->uuid}");
        $request->assertStatus(403);
    }

    #[Test]
    public function user_can_see_his_orders(): void
    {
        $arr = $this->createUserAndOrder();
        $request = $this->actingAs($arr[0])->getJson('api/v1/orders');
        $request->assertStatus(200);
        $request->assertJsonCount(1, 'data');
    }

    #[Test]
    public function support_can_see_all_orders(): void
    {
        $this->createUserAndOrder();
        $support = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);
        $request = $this->actingAs($support->user)->getJson('api/v1/orders');
        $request->assertStatus(200);
        $request->assertJsonCount(1, 'data');
    }

    #[Test]
    public function support_can_see_any_order(): void
    {
        $arr = $this->createUserAndOrder();
        $support = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);
        $request = $this->actingAs($support->user)->getJson("api/v1/orders/{$arr[2]->uuid}");
        $request->assertStatus(200);
    }

    #[Test]
    public function guest_cannot_create_order(): void
    {
        $arr = $this->createUserAndOrder();
        $request = $this->postJson('api/v1/orders', [
            'user_id' => $arr[0]->id,
            'address_id' => Address::factory()->create([
                'user_id' => $arr[0]->id,
            ])->id,
        ]);

        $request->assertStatus(401);
    }

    #[Test]
    public function user_can_create_order(): void
    {
        //
    }

    #[Test]
    public function guest_cannot_update_any_order(): void
    {
        $arr = $this->createUserAndOrder();
        $request = $this->putJson("api/v1/orders/{$arr[2]->uuid}", [
            'address_id' => 1,
        ]);

        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_update_his_order_with_not_his_address(): void
    {
        $arr = $this->createUserAndOrder();
        $address = Address::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);
        $request = $this->actingAs($arr[0])->putJson("api/v1/orders/{$arr[2]->uuid}", [
            'address_id' => $address->id,
        ]);

        $request->assertStatus(422);
    }

    #[Test]
    public function user_cannot_update_not_his_order(): void
    {
        $arr = $this->createUserAndOrder();
        $user = User::factory()->create();
        $request = $this->actingAs($user)->putJson("api/v1/orders/{$arr[2]->uuid}", [
            'address_id' => 1,
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function user_can_update_his_orders(): void
    {
        $arr = $this->createUserAndOrder();
        $address = Address::factory()->create([
            'user_id' => $arr[0]->id,
        ]);
        $request = $this->actingAs($arr[0])->putJson("api/v1/orders/{$arr[2]->uuid}", [
            'address_id' => $address->id,
        ]);

        $request->assertStatus(200);
    }
}
