<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'expense_name' => ['required', 'string'],
            'expense_amount' => ['required', 'numeric', 'min:1',
                function ($attribute, $value, $fail) {
                    if (str_contains($value, '.') && !preg_match('/^\d+\.\d{2}$/', $value)) {
                        $fail('The expense amount must have exactly two decimal places.');
                    }
                },
            ],
        ];
    }
}
