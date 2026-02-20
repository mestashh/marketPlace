<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;

class StoreSellerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->seller === null;
    }

    public function prepareForValidation(): array
    {
        $is_active = $this->input('is_active');
        $rating = $this->input('rating');
        if (! is_float($rating)) {
            $rating = (float) $rating;
        }

        return [
            'is_active' => (bool) $is_active,
            'rating' => $rating,
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
            'shop_name' => ['required', 'string', 'min:3', 'max:60', 'unique:sellers,shop_name'], // не уверен как правильно писать уникальность.
            'rating' => ['sometimes', 'numeric', 'min:0', 'max:5'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
