<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user('seller');
        $shop = $this->route('shop');
        $product = $this->route('product');
        if (! $user || ! $shop || ($user->id !== $shop->seller_id) || ($shop->status !== 'active') || ($product->shop_id !== $shop->id)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['integer', 'exists:categories,id'],
            'name' => ['string', 'max:50'],
            'description' => ['string', 'max:100'],
        ];
    }
}
