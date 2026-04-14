<?php

namespace Tests\Feature;

use App\Enums\StatusEnum;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CartItemTest extends TestCase
{
    use RefreshDataBase;

    private function createUserAndCartItem(StatusEnum $status): array
    {
        $user = User::factory()->create();
        $seller = Seller::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);
        $shop = Shop::factory()->create([
            'seller_id' => $seller->id,
        ]);
        $product = Product::factory()->create([
            'category_id' => Category::create([
                'parent_id' => null,
                'name' => 'Toys',
            ])->id,
            'shop_id' => $shop->id,
        ]);
        $productVariant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'price' => 1000,
            'access_status' => $status->value,
        ]);
        $cartItem = CartItem::factory()->create([
            'cart_id' => $user->cart->id,
            'product_variant_id' => $productVariant->id,
        ]);

        return [$user, $cartItem, $productVariant];
    }

    #[Test]
    public function guest_cannot_see_any_cart(): void
    {
        $arr = $this->createUserAndCartItem(StatusEnum::ACCESS);
        $request = $this->getJson('api/v1/cart');
        $request->assertStatus(401);
    }

    #[Test]
    public function user_can_see_his_cart(): void
    {
        $arr = $this->createUserAndCartItem(StatusEnum::ACCESS);
        $request = $this->actingAs($arr[0])->getJson('api/v1/cart');
        $request->assertStatus(200);
    }

    #[Test]
    public function user_can_add_item_in_cart(): void
    {
        $arr = $this->createUserAndCartItem(StatusEnum::ACCESS);
        $request = $this->actingAs($arr[0])->postJson('api/v1/cart/items', [
            'product_variant_id' => $arr[2]->id,
            'quantity' => 1,
        ]);
        $request->assertStatus(200);
    }

    #[Test]
    public function user_can_update_item_in_cart(): void
    {
        $arr = $this->createUserAndCartItem(StatusEnum::ACCESS);
        $request = $this->actingAs($arr[0])->putJson("api/v1/cart/items/{$arr[1]->uuid}", [
            'quantity' => 1,
        ]);
        $request->assertStatus(200);
    }

    #[Test]
    public function user_can_delete_item_in_cart(): void
    {
        $arr = $this->createUserAndCartItem(StatusEnum::ACCESS);
        $request = $this->actingAs($arr[0])->deleteJson("api/v1/cart/items/{$arr[1]->uuid}");
        $request->assertStatus(204);
    }
}
