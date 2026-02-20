<?php

namespace App\Http\Requests\Address;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
            'country' => ['nullable', 'string', 'min: 3', 'max: 255'],
            'city' => ['nullable', 'string', 'min: 3', 'max: 255'],
            'street' => ['nullable', 'string', 'min: 1', 'max: 255'],
            'house' => ['nullable', 'string', 'min: 1', 'max: 255'],
            'phone' => ['nullable', 'string', 'min: 3', 'max: 20'],
            'description' => ['nullable', 'string', 'max: 255'],
        ];
    }
}
