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
            return view('weather.index', compact('forecast'));
        }

        return view('weather.index')->withErrors('Không thể lấy dữ liệu dự báo thời tiết');
    }

    // Phương thức gửi câu hỏi đến ChatGPT
    public function sendToChatGPT(Request $request)
    {
        $apiKey = env('OPENAI_API_KEY');
        $model = 'gpt-3.5-turbo';
        $userInput = $request->input('question');

        try {
            // Gửi yêu cầu đến OpenAI API
            $response = Http::withHeaders([
                'Authorization' => "Bearer $apiKey",
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $userInput,
                    ]
                ],
            ]);

            if ($response->successful()) {
                $result = $response->json();
                return response()->json(['response' => $result['choices'][0]['message']['content'] ?? 'Không có phản hồi từ ChatGPT']);
            }

            return response()->json(['response' => 'Lỗi từ OpenAI API'], 500);
        } catch (\Exception $e) {
            return response()->json(['response' => 'Có lỗi xảy ra khi kết nối với OpenAI: ' . $e->getMessage()], 500);
        }
    }
}
