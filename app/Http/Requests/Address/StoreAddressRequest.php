<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'country' => ['required', 'string', 'min: 3', 'max: 255'],
            'city' => ['required', 'string', 'min: 3', 'max: 255'],
            'street' => ['required', 'string', 'min: 1', 'max: 255'],
            'house' => ['required', 'string', 'min: 1', 'max: 255'],
            'phone' => ['required', 'string', 'min: 5', 'max: 20'],
            'description' => ['nullable', 'string', 'max: 255'],
        ];
    }
}
