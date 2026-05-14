<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SwipeController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index'])->name('landing');
Route::get('/create', [EventController::class, 'create'])->name('event.create');
Route::post('/create', [EventController::class, 'store'])->name('event.store');

Route::get('/wishlist/create', [WishlistController::class, 'create'])->name('wishlist.create');
Route::post('/wishlist/create', [WishlistController::class, 'store'])->name('wishlist.store');
Route::get('/wishlist/{wishlist}', [WishlistController::class, 'showPublic'])->name('wishlist.public');
Route::get('/wishlist/edit/{privateSlug}', [WishlistController::class, 'showPrivate'])->name('wishlist.private');
Route::post('/wishlist/edit/{privateSlug}/items', [WishlistController::class, 'storeItem'])->name('wishlist.store-item');
Route::delete('/wishlist/edit/{privateSlug}/items/{item}', [WishlistController::class, 'destroyItem'])->name('wishlist.destroy-item');
Route::post('/wishlist/{wishlist}/create-event', [WishlistController::class, 'createEvent'])->name('wishlist.create-event');

Route::prefix('/{event:slug}')->group(function () {
    Route::get('/', [EventController::class, 'show'])->name('event.show');
    Route::post('/gifts', [EventController::class, 'storeGift'])->name('event.store-gift');
    Route::get('/results', [EventController::class, 'results'])->name('event.results');
    Route::post('/pledge', [EventController::class, 'updatePledge'])->name('event.pledge');

    Route::post('/messages', [MessageController::class, 'store'])->name('event.store-message');

    Route::get('/swipe', [SwipeController::class, 'index'])->name('swipe.index');
    Route::post('/join', [SwipeController::class, 'join'])->name('swipe.join');
    Route::post('/swipe', [SwipeController::class, 'store'])->name('swipe.store');
});
