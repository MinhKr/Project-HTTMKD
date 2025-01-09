<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Agriculture Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        function showTab(tabId) {
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                tab.style.display = 'none';
            });

            document.getElementById(tabId).style.display = 'block';

            const links = document.querySelectorAll('.sidebar a');
            links.forEach(link => {
                link.classList.remove('active');
            });

            document.querySelector(`[href="#${tabId}"]`).classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', () => {
            showTab('weather');
        });
    </script>
</head>
<body>
    <div class="sidebar">
        <h2>Quản lý Nông nghiệp</h2>
        <a href="#dashboard" onclick="showTab('dashboard')">Bảng điều khiển</a>
        <a href="#weather" onclick="showTab('weather')" class="active">Thời tiết</a>
        <a href="#season" onclick="showTab('season')">Mùa vụ</a>
        <a href="#chatbot" onclick="showTab('chatbot')">Chatbot</a>
    </div>

    <div class="content">
        <!-- Weather Tab -->
        <div id="weather" class="tab-content">
            <h1>Thông tin thời tiết tại {{ $cityName }}</h1>
            
            @if($errors->any())
                <p class="error-message">{{ $errors->first() }}</p>
            @elseif(isset($dailyForecast) && count($dailyForecast) > 0)
                <div class="weather-container">
                    @foreach($dailyForecast as $date => $weather)
                        <div class="weather-card">
                            <p><strong>{{ \Carbon\Carbon::parse($weather['dt_txt'])->format('D, M j') }}</strong></p>
                            <img src="https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}.png" alt="{{ $weather['weather'][0]['description'] }}">
                            <p>{{ round($weather['main']['temp']) }}°C</p>
                            <p>{{ ucfirst($weather['weather'][0]['description']) }}</p>
                            <p>Độ ẩm: {{ $weather['main']['humidity'] }}%</p>
                            <p>Tốc độ gió: {{ round($weather['wind']['speed']) }} m/s</p>
                        </div>
                    @endforeach
                </div>

                <!-- Thông tin thời tiết đầu tiên -->
                @php
                    $firstWeather = reset($dailyForecast);
                @endphp
                <div class="weather-summary">
                    <h2>Thông tin thời tiết hiện tại</h2>
                    <p>
                        <img src="https://openweathermap.org/img/wn/{{ $firstWeather['weather'][0]['icon'] }}.png" 
                             alt="{{ $firstWeather['weather'][0]['description'] }}" style="width: 50px; height: 50px; vertical-align: middle;">
                        <strong style="font-size: 48px;">{{ round($firstWeather['main']['temp']) }}°C</strong>
                        <span style="font-size: 24px;"> - {{ ucfirst($firstWeather['weather'][0]['description']) }}</span>
                    </p>
                    <p class="feels-like"><strong>Cảm thấy như: {{ round($firstWeather['main']['feels_like']) }}°C</strong></p>
                    <p><strong>Thời gian: {{ \Carbon\Carbon::now()->format('D, M j H:i') }}</strong></p>
                    <div class="additional-info">
                        <p><strong>Độ ẩm:</strong> {{ $firstWeather['main']['humidity'] }}%</p>
                        <p><strong>Tốc độ gió:</strong> {{ round($firstWeather['wind']['speed']) }} m/s</p>
                        <p><strong>Áp suất:</strong> {{ $firstWeather['main']['pressure'] }} hPa</p>
                    </div>
                </div>
            @else
                <p>Không có dữ liệu thời tiết nào.</p>
            @endif
        </div>

        <!-- Dashboard Tab -->
        <div id="dashboard" class="tab-content" style="display: none;">
            <h1>Bảng điều khiển</h1>
            <p>Chào mừng bạn đến với Bảng điều khiển Nông nghiệp thông minh!</p>
        </div>

        <!-- Mùa vụ Tab -->
<div id="season" class="tab-content" style="display: none;">
    <h1>Quản lý Mùa vụ</h1>
    <div id="season-form">
        <h2>Thêm Mùa vụ</h2>
        <input type="text" id="seasonName" placeholder="Tên mùa vụ" required>
        <input type="text" id="cropType" placeholder="Loại cây trồng" required>
        <input type="date" id="startDate" required>
        <input type="date" id="endDate" required>
        <button id="addSeasonBtn" onclick="addSeason()">Thêm</button>
    </div>

    <h2>Danh sách Mùa vụ</h2>
    <div id="season-list"></div>
</div>

<script>
    function addSeason() {
        const seasonName = document.getElementById('seasonName').value;
        const cropType = document.getElementById('cropType').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        if (seasonName && cropType && startDate && endDate) {
            const seasonList = document.getElementById('season-list');
            const seasonCard = document.createElement('div');
            seasonCard.className = 'season-card';
            seasonCard.innerHTML = `
                <span>${seasonName} - ${cropType} (${startDate} đến ${endDate})</span>
                <button onclick="removeSeason(this)">Xóa</button>
            `;
            seasonList.appendChild(seasonCard);aa

            // Reset input fields
            document.getElementById('seasonName').value = '';
            document.getElementById('cropType').value = '';
            document.getElementById('startDate').value = '';
            document.getElementById('endDate').value = '';
        } else {
            alert('Vui lòng điền tất cả thông tin.');
        }
    }

    function removeSeason(button) {
        button.parentElement.remove(); // Xóa phần tử cha (seasonCard)
    }
</script>

        <!-- Chatbot Tab -->
        <div id="chatbot" class="tab-content" style="display: none;">
            <h1>Chatbot</h1>
            <div id="chat-container">
                <div id="chat-header">Chatbot</div>
                <div id="chat-box"></div>
                <input type="text" id="message" name="message" placeholder="Nhập tin nhắn của bạn...">
                <button id="send-btn">Gửi</button>
            </div>
            <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
            <script>
                let baseUrl = "{{ url('/') }}";
                // Setup CSRF token for AJAX
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                })
                $('#send-btn').on('click', function(){
                    $value = $('#message').val();
                    $('#chat-box').append('<div class="user-message"><span class="message">' + $value + '</span></div>');

                    $.ajax({
                        type: 'POST',
                        url: baseUrl + '/question',
                        data: {
                            'input': $value
                        },
                        success: function(data){
                            $('#chat-box').append('<div class="bot-message"><span class="message">' + data + '</span></div>');
                            $value = $('#message').val('');
                        },
                        error: function(){
                            alert('Show at could not reply!');
                        }
                    })
                })
            </script>
        </div>
</body>
</html>
