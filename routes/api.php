<?php

use App\Http\Controllers\VenueController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::prefix('weather')->group(function () {
    Route::get('check', [WeatherController::class, 'check']);
    Route::get('forecast', [WeatherController::class, 'forecast']);
});

Route::prefix('venue')->group(function () {
    Route::get('categories', [VenueController::class, 'categories']);
    Route::get('search', [VenueController::class, 'search']);
    Route::get('{id}', [VenueController::class, 'details']);
});
