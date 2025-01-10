<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller
{
    // Phương thức hiển thị thông tin thời tiết
    public function index()
    {
        $lat = 21.028511; // Hà Nội
        $lon = 105.804817;
        $apiKey = env('OPENWEATHER_API_KEY'); // API Key cho OpenWeather

        // Gửi yêu cầu lấy dữ liệu thời tiết
        $response = Http::get("https://api.openweathermap.org/data/2.5/forecast", [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $apiKey,
            'units' => 'metric',
            'lang' => 'vi',
        ]);

        if ($response->successful()) {
            $forecast = $response->json();

            // Lấy tên thành phố từ dữ liệu dự báo
            $cityName = $forecast['city']['name'] ?? 'Không xác định';

            // Lọc ra dữ liệu cho 5 ngày (1 lần mỗi ngày)
            $dailyForecast = [];
            foreach ($forecast['list'] as $weather) {
                $date = \Carbon\Carbon::parse($weather['dt_txt'])->format('Y-m-d');
                if (!isset($dailyForecast[$date])) {
                    $dailyForecast[$date] = $weather;
                }
            }

            return view('weather.index', compact('dailyForecast', 'cityName'));
        }

        return view('weather.index')->withErrors('Không thể lấy dữ liệu dự báo thời tiết');
    }
}