<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSwipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'gift_idea_id' => ['required', 'exists:gift_ideas,id'],
            'liked' => ['required', 'boolean'],
        ];
    }
}
