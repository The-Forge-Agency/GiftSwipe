<?php

use App\Http\Controllers\Api\ScrapeController;
use App\Http\Controllers\Api\UploadImageController;
use Illuminate\Support\Facades\Route;

Route::post('/scrape-url', ScrapeController::class)->middleware('throttle:10,1');
Route::post('/upload-image', UploadImageController::class)->middleware('throttle:20,1');
