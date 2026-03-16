<?php

namespace App\Http\Requests\ProductImage;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductImageRequest extends FormRequest
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
            'path' => ['sometimes', 'string', 'min:10', 'max:255'],
            'is_main' => ['sometimes', 'boolean'],
        ];
    }
}
