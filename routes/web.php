<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

Route::get('/', function () {
    return view('weather.index');
});

Route::get('/', [WeatherController::class, 'index']);
