<?php

namespace App\Http\Requests;

use App\Services\UrlScraperService;
use Illuminate\Foundation\Http\FormRequest;

class StoreGiftIdeaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->url) {
            $this->merge(['url' => UrlScraperService::cleanUrl($this->url)]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'url' => ['nullable', 'url', 'max:2048'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'description' => ['nullable', 'string', 'max:500'],
            'price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
