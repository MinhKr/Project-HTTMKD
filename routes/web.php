<?php

use App\Http\Controllers\ChatBotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\GeminiController;

Route::get('/gem', function () {
    return view('gemini');
});
// Route hiển thị thời tiết
Route::get('/', [WeatherController::class, 'index'])->name('weather.index');

// Route gửi câu hỏi đến ChatGPT
Route::post('/chat', [ChatBotController::class, 'sendToChatGPT']);
Route::post('/question', [GeminiController::class, 'sendToGemini']);
