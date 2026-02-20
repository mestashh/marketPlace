<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSellerProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $seller = $this->route('seller');
        if ($user === null || $user->id !== $seller->user_id) {
            return false;
        }

        return true;
    }

    public function prepareForValidation(): array
    {
        $is_active = (bool) $this->input('is_active');

        return [
            'is_active' => $is_active,
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shop_name' => ['sometimes', 'string', 'min:3', 'max:60', 'unique:sellers,shop_name'],
            'is_active' => ['sometimes', 'boolean'],
            'rating' => ['prohibited'],
        ];
    }
}
