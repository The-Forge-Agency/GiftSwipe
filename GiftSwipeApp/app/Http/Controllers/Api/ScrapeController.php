<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UrlScraperService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScrapeController extends Controller
{
    public function __invoke(Request $request, UrlScraperService $scraper): JsonResponse
    {
        $request->validate([
            'url' => ['required', 'url'],
        ]);

        $data = $scraper->scrape($request->input('url'));

        return response()->json($data);
    }
}
