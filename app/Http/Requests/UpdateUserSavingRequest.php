<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSavingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'saving_name' => ['required', 'string'],
            'saving_amount' => ['required', 'numeric', 'min:1',
                function ($attribute, $value, $fail) {
                    if (str_contains($value, '.') && !preg_match('/^\d+\.\d{2}$/', $value)) {
                        $fail('The saving amount must have exactly two decimal places.');
                    }
                },
            ],
            'saving_goal' => ['required', 'numeric', 'min:1',
                function ($attribute, $value, $fail) {
                    if (str_contains($value, '.') && !preg_match('/^\d+\.\d{2}$/', $value)) {
                        $fail('The saving goal must have exactly two decimal places.');
                    }
                },
            ],
        ];
    }
}
