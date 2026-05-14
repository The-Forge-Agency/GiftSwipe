<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UploadImageController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'max:5120'],
        ]);

        $path = $request->file('image')->store('gift-images', 'public');

        return response()->json([
            'image_url' => asset('storage/'.$path),
        ]);
    }
}
