<?php

namespace App\Http\Requests\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'name' => ['required', 'string', 'min:1', 'max:50'],
            'description' => ['sometimes', 'string', 'min:1', 'max:255'],
            'price' => ['required', 'decimal:0,2', 'min:50', 'max:100000'],
            'stock' => ['required', 'integer', 'min:5', 'max:100000'],
            'sku' => ['required', 'string', 'min:10', 'max:10', 'regex:/^SKU-/'],
        ];
    }
}
