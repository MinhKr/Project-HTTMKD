<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    public function index()
    {
        $lat = 21.028511; 
        $lon = 105.804817; 
        $apiKey = env('OPENWEATHER_API_KEY');

        
        $response = Http::get("https://api.openweathermap.org/data/2.5/forecast?lat={$lat}&lon={$lon}&appid={$apiKey}&units=metric&lang=vi");

        if ($response->successful()) {
            $forecast = $response->json(); 
            return view('weather.index', compact('forecast'));

        }

        return view('index')->withErrors('Không thể lấy dữ liệu dự báo thời tiết');
    }
}
