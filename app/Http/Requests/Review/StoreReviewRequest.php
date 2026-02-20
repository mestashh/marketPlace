<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user('web');
        $product = $this->route('product');

        if (! $user || ! $product) {
            return false;
        }

        return DB::table('orders')
            ->join('shop_orders', 'shop_orders.order_id', '=', 'orders.id')
            ->join('order_items', 'order_items.shop_order_id', '=', 'shop_orders.id')
            ->join('product_variants', 'product_variants.id', '=', 'order_items.product_variant_id')
            ->where('orders.user_id', $user->id)
            ->where('product_variants.product_id', $product->id)
            ->whereIn('orders.status', 'completed')
            ->whereIn('shop_orders.status', 'delivered')
            ->exists();
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'text' => ['required', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user('web');
            $product = $this->route('product');

            if (! $user || ! $product) {
                return;
            }

            $alreadyReviewed = DB::table('reviews')
                ->where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->exists();

            if ($alreadyReviewed) {
                $validator->errors()->add('review', 'Вы уже оставляли отзыв на этот товар.');
            }
        });
    }
}
