<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Agriculture Dashboard</title>
    <style>
        /* Tổng quan */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            color: #333;
        }
        h2 {
            margin-bottom: 15px;
            font-size: 1.5rem;
            color: #4CAF50;
        }
        p {
            margin: 5px 0;
            line-height: 1.6;
        }

        /* Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header */
        .header {
            grid-column: span 12;
            text-align: center;
            background: #4CAF50;
            color: white;
            padding: 15px 0;
            font-size: 1.8rem;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Weather */
        .weather {
            grid-column: span 6;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .weather-summary {
            text-align: center;
            margin-bottom: 20px;
        }

        .weather-summary img {
            width: 50px;
            height: 50px;
        }

        .weather-summary p {
            margin: 10px 0;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .weather-details {
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        .weather-details div {
            margin-bottom: 20px;
        }

        .weather-details h3 {
            font-size: 1rem;
            margin-bottom: 10px;
            color: #4CAF50;
        }

        .weather-details img {
            width: 40px;
            height: 40px;
            vertical-align: middle;
        }

        .weather-details p {
            margin: 5px 0;
            font-size: 0.9rem;
        }

        /* Dashboard */
        .dashboard {
            grid-column: span 6;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="grid">
        <!-- Header -->
        <div class="header">World Peas - Smart Agriculture Dashboard</div>

        <!-- Weather -->
        <div class="card weather">
            <!-- Tóm tắt thời tiết -->
            <div class="weather-summary">
                <img src="https://openweathermap.org/img/wn/{{ $forecast['list'][0]['weather'][0]['icon'] }}.png" alt="Weather Icon">
                <p>{{ $forecast['city']['name'] }}</p>
                <p>Nhiệt độ: {{ $forecast['list'][0]['main']['temp'] }} °C</p>
                <p>Trạng thái: {{ $forecast['list'][0]['weather'][0]['description'] }}</p>
                <p>Độ ẩm: {{ $forecast['list'][0]['main']['humidity'] }}%</p>
                <p>Tốc độ gió: {{ $forecast['list'][0]['wind']['speed'] }} m/s</p>
            </div>

            <!-- Chi tiết thời tiết -->
            <div class="weather-details">
                @if ($errors->any())
                    <p><strong>Lỗi:</strong> Không thể lấy dữ liệu thời tiết.</p>
                @elseif (isset($forecast['list']))
                    @foreach ($forecast['list'] as $index => $item)
                        @if ($index < 5) <!-- Hiển thị 5 dự báo -->
                            <div>
                                <h3>{{ $item['dt_txt'] }}</h3>
                                <img src="https://openweathermap.org/img/wn/{{ $item['weather'][0]['icon'] }}.png" alt="Weather Icon">
                                <p>Nhiệt độ: {{ $item['main']['temp'] }} °C</p>
                                <p>Trạng thái: {{ $item['weather'][0]['description'] }}</p>
                                <p>Độ ẩm: {{ $item['main']['humidity'] }}%</p>
                                <p>Tốc độ gió: {{ $item['wind']['speed'] }} m/s</p>
                                <hr>
                            </div>
                        @endif
                    @endforeach
                @else
                    <p>Không có dữ liệu thời tiết.</p>
                @endif
            </div>
        </div>

        <!-- Dashboard 1 -->
        <div class="card dashboard">
            <h2>Dashboard Cây Trồng</h2>
            <p>
                Giám sát tình trạng cây trồng theo thời gian thực, bao gồm nhiệt độ, độ ẩm và chỉ số phát triển.
            </p>
            <ul>
                <li>Loại cây: Cà chua</li>
                <li>Nhiệt độ đất: 25°C</li>
                <li>Độ ẩm đất: 70%</li>
                <li>Chỉ số phát triển: Tốt</li>
            </ul>
        </div>

        <!-- Dashboard 2 -->
        <div class="card dashboard">
            <h2>Dashboard Môi Trường</h2>
            <p>
                Dữ liệu môi trường khu vực canh tác, bao gồm nhiệt độ không khí và chất lượng ánh sáng.
            </p>
            <ul>
                <li>Nhiệt độ không khí: 30°C</li>
                <li>Độ ẩm không khí: 60%</li>
                <li>Ánh sáng: Tốt (8/10)</li>
                <li>Lượng mưa dự báo: 5mm</li>
            </ul>
        </div>
    </div>
</body>
</html>
