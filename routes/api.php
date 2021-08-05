<?php

use App\Http\Controllers\VenueController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::get('weather', [WeatherController::class, 'check']);

Route::prefix('venue')->group(function () {
    Route::get('categories', [VenueController::class, 'categories']);
});
