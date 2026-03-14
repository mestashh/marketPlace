<?php

namespace App\Http\Requests\PayoutMethod;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductVariantRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'min:1', 'max:50'],
            'description' => ['sometimes', 'string', 'min:1', 'max:255'],
            'price' => ['sometimes', 'decimal:0,2', 'min:50', 'max:100000'],
            'stock' => ['sometimes', 'integer', 'min:5', 'max:100000'],
            'sku' => ['sometimes', 'string', 'min:10', 'max:10', 'contains:SKU-'],
        ];
    }
}
