<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GeminiAPI\Client;
use GeminiAPI\Resources\Parts\TextPart;

class GeminiController extends Controller
{
    public function sendToGemini(Request $request)
    {
        //dd($request);
        $question = $request->input;
        // Khởi tạo API
        $client = new Client(env('GEMINI_API_KEY'));
        // Sử dụng Gemini API để trả lời câu hỏi
        $response = $client->geminiPro()->generateContent(
            new TextPart($question),
        );
        // Trả về câu trả lời
        $answer = $response->text();
        return $answer;
    }
}
