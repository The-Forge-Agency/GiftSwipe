<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'birthday_person_name' => ['required', 'string', 'max:50'],
            'birthday_date' => ['required', 'date', 'after_or_equal:today'],
        ];
    }
}
