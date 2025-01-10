<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

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

    public function test(){
        $client = new Client();

        // Khai báo url api superset
        $supersetApiUrl = "http://82.112.237.22:8088/api/v1";

        $responseTokenLogin = $client->request('POST', $supersetApiUrl . "/security/login", [
            'json' => [
                'username' => 'admin',
                'password' => 'admin',
                'provider' => 'db',
                'refresh' => false
            ]
        ]);

        $bodyTokenLogin = json_decode($responseTokenLogin->getBody(), true);
        $token = $bodyTokenLogin['access_token'];

        $responseGetGuestToken = $client->request('POST', $supersetApiUrl . "/security/guest_token/", [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ],
            'json' => [
                'resources' => [
                    [
                        'type' => 'dashboard',
                        'id' => '595d7829-fd5b-49db-97ef-f6e597b81d32' // ID dashboard
                    ]
                ],
                'user' => [
                    'username' => 'guest_user',
                    'first_name' => 'guest_user',
                    'last_name' => 'guest_user',
                    'email' => 'guest_user'
                ],
                'rls' => [
                    
                ]
            ]
        ]);

        $bodyGuestToken = json_decode($responseGetGuestToken->getBody(), true);
        $guestToken = $bodyGuestToken['token'];

        return response()->json($guestToken);
    }
}