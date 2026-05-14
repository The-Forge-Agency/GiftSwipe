<?php

use App\Http\Controllers\Api\ScrapeController;
use Illuminate\Support\Facades\Route;

Route::post('/scrape-url', ScrapeController::class)->middleware('throttle:10,1');
