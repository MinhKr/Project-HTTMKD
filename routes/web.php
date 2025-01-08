<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;

// Route hiển thị thời tiết
Route::get('/', [WeatherController::class, 'index'])->name('weather.index');

// Route gửi câu hỏi đến ChatGPT
Route::post('/chat', [WeatherController::class, 'sendToChatGPT'])->name('chat.send');
