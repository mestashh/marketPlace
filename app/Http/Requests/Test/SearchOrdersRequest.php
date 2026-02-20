<?php

namespace App\Http\Requests\Test;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchOrdersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user !== null && $user->seller;
    }

    public function prepareForValidation(): array
    {
        $status = strtolower($this->input('status'));
        $min_total = (float) $this->input('min_total');
        $max_total = (float) $this->input('max_total');

        return [
            'status' => $status,
            'min_total' => $min_total,
            'max_total' => $max_total,
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
            'status' => ['sometimes', 'string', Rule::in(['paid', 'shipped', 'canceled'])],
            'min_total' => ['sometimes', 'numeric', 'min:0'],
            'max_total' => ['sometimes', 'numeric', 'min:0'],
            'date_from' => ['sometimes', 'date'],
            'date_to' => ['sometime', 'date', 'after_or_equal:date_from'],
        ];
    }
}
