<?php

namespace Tests\Feature;

use App\Enums\AdminRoleEnum;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\ShopOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDataBase;

    private function createConversationWithMessages(): array
    {
        $user = User::factory()->create();
        Address::factory()->create([
            'user_id' => $user->id,
        ]);
        $seller = Seller::factory()->create();
        $admin = Admin::factory()->create();

        $conversation = Conversation::create([
            'shop_order_id' => ShopOrder::factory()->create([
                'order_id' => Order::factory()->create()->id,
                'shop_id' => Shop::factory()->create(['seller_id' => $seller->id])->id,
            ])->id,
            'user_id' => $user->id,
            'seller_id' => $seller->id,
            'admin_id' => $admin->id,
        ]);

        for ($i = 0; $i < 10; $i++) {
            Message::factory()->create([
                'conversation_id' => $conversation->id,
                'user_id' => fake()->randomElement([$user->id, $seller->user->id, $admin->user->id]),
            ]);
        }

        return [$conversation, $user, $seller, $admin];
    }

    #[Test]
    public function guest_cannot_see_messages(): void
    {
        $arr = $this->createConversationWithMessages();
        $request = $this->getJson("api/v1/conversation/{$arr[0]->uuid}/messages");
        $request->assertStatus(401);
    }

    #[Test]
    public function user_cannot_see_messages_in_not_his_conversations(): void
    {
        $user = User::factory()->create();
        $arr = $this->createConversationWithMessages();
        $request = $this->actingAs($user)->getJson("api/v1/conversation/{$arr[0]->uuid}/messages");
        $request->assertStatus(403);
    }

    #[Test]
    public function user_can_see_messages_in_his_conversations(): void
    {
        $arr = $this->createConversationWithMessages();
        $request = $this->actingAs($arr[1])->getJson("api/v1/conversation/{$arr[0]->uuid}/messages");
        $request->assertStatus(200);
        $request->assertJsonCount(10, 'data');
    }

    #[Test]
    public function seller_can_see_messages_in_his_conversations(): void
    {
        $arr = $this->createConversationWithMessages();
        $request = $this->actingAs($arr[2]->user)->getJson("api/v1/conversation/{$arr[0]->uuid}/messages");
        $request->assertStatus(200);
        $request->assertJsonCount(10, 'data');
    }

    #[Test]
    public function seller_cannot_see_messages_in_not_his_conversations(): void
    {
        $seller = Seller::factory()->create([
            'user_id' => User::factory()->create(),
        ]);
        $arr = $this->createConversationWithMessages();
        $request = $this->actingAs($seller->user)->getJson("api/v1/conversation/{$arr[0]->uuid}/messages");
        $request->assertStatus(403);
    }

    #[Test]
    public function support_can_see_messages_in_not_his_conversations(): void
    {
        $support = Admin::factory()->create([
            'user_id' => User::factory()->create(),
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);
        $arr = $this->createConversationWithMessages();
        $request = $this->actingAs($support->user)->getJson("api/v1/conversation/{$arr[0]->uuid}/messages");
        $request->assertStatus(200);
    }

    #[Test]
    public function user_can_send_message_in_conversation(): void
    {
        $arr = $this->createConversationWithMessages();

        $request = $this->actingAs($arr[1])->postJson("api/v1/conversation/{$arr[0]->uuid}/messages", [
            'text' => 'Test',
        ]);

        $request->assertStatus(201);
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $arr[0]->id,
            'text' => 'Test',
            'user_id' => $arr[1]->id,
        ]);
    }

    #[Test]
    public function user_cannot_send_message_in_not_his_conversation(): void
    {
        $arr = $this->createConversationWithMessages();
        $user = User::factory()->create();
        $request = $this->actingAs($user)->postJson("api/v1/conversation/{$arr[0]->uuid}/messages", [
            'text' => 'Test',
        ]);

        $request->assertStatus(403);
    }

    #[Test]
    public function seller_can_send_message_in_conversation(): void
    {
        $arr = $this->createConversationWithMessages();

        $request = $this->actingAs($arr[2]->user)->postJson("api/v1/conversation/{$arr[0]->uuid}/messages", [
            'text' => 'Test',
        ]);

        $request->assertStatus(201);
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $arr[0]->id,
            'text' => 'Test',
            'user_id' => $arr[2]->user->id,
        ]);
    }

    #[Test]
    public function support_can_send_message_in_conversation(): void
    {
        $arr = $this->createConversationWithMessages();

        $request = $this->actingAs($arr[3]->user)->postJson("api/v1/conversation/{$arr[0]->uuid}/messages", [
            'text' => 'Test',
        ]);

        $request->assertStatus(201);
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $arr[0]->id,
            'text' => 'Test',
            'user_id' => $arr[3]->user->id,
        ]);
    }

    #[Test]
    public function support_cannot_send_message_in_not_his_conversation(): void
    {
        $arr = $this->createConversationWithMessages();
        $support = Admin::factory()->create([
            'user_id' => User::factory()->create()->id,
            'role' => AdminRoleEnum::SUPPORT->value,
        ]);
        $request = $this->actingAs($support->user)->postJson("api/v1/conversation/{$arr[0]->uuid}/messages", [
            'text' => 'Test',
        ]);

        $request->assertStatus(403);
    }

}
