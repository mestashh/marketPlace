<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->seller === null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(['Paid', 'Shipped', 'Canceled'])],
            'paid_at' => ['required_if:status,Paid', 'prohibited_unless:status,Paid', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Статус должен быть Paid, Shipped, Canceled',
            'paid_at.required' => 'Для статуса нужно указать paid_at',
            'paid_at.prohibited' => 'Если статус не paid, paid_at отправлять нельзя',
        ];
    }
}
