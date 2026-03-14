<?php

namespace App\Http\Requests\Payout;

use App\Enums\PayoutMethodEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

/**
 * @property mixed $payout_method
 */
class UpdatePayoutMethodRequest extends FormRequest
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
            'payout_method' => ['required', new Enum(PayoutMethodEnum::class)],
        ];
    }
}
