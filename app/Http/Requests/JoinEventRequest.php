<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JoinEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:30'],
            'budget_max' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
