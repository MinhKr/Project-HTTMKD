<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Agriculture Dashboard</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>
<body>
    <div class="grid">
        <div class="header">World Peas - Smart Agriculture Dashboard</div>

        <!-- Weather -->
        <div class="card weather">
            @if(isset($forecast))
                <div>
                    <img src="https://openweathermap.org/img/wn/{{ $forecast['list'][0]['weather'][0]['icon'] }}.png" alt="Weather Icon">
                    <p>Thành phố: {{ $forecast['city']['name'] }}</p>
                    <p>Nhiệt độ: {{ $forecast['list'][0]['main']['temp'] }} °C</p>
                    <p>Trạng thái: {{ $forecast['list'][0]['weather'][0]['description'] }}</p>
                    <p>Độ ẩm: {{ $forecast['list'][0]['main']['humidity'] }}%</p>
                </div>
            @else
                <p>Không thể hiển thị thông tin thời tiết.</p>
            @endif
        </div>

        <!-- Chatbot -->
        <div class="card chatbot">
            <h2>Trợ Lý ChatGPT</h2>
            <textarea id="question" placeholder="Nhập câu hỏi..."></textarea>
            <button id="sendBtn">Gửi</button>
            <p>Start chatting with Chat GPT AI below</p>
            <div id="response" class="response"></div>
        </div>
    </div>

    <script>
        document.getElementById('sendBtn').addEventListener('click', function () {
            const question = document.getElementById('question').value;

            fetch('/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ question }),
            })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('response').innerText = data.response;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('response').innerText = 'Có lỗi xảy ra!';
                });
        });
    </script>
</body>
</html>
