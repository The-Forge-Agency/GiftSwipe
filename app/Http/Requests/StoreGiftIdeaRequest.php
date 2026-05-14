<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGiftIdeaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'url' => ['nullable', 'url', 'max:500'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
