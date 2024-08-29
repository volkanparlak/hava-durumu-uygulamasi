<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hava Durumu Uygulaması</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            margin: 0;
            padding: 0;
            color: #333;
            text-align: center;
        }
        .container {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        header h1 {
            color: #1e90ff;
        }
        .search-section {
            margin-bottom: 20px;
        }
        input[type="text"], button {
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
            margin: 5px 0;
        }
        input[type="text"] {
            border: 1px solid #ddd;
            width: calc(100% - 24px);
        }
        button {
            border: none;
            background-color: #1e90ff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #1c86ee;
        }
        .weather-card {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .weather-header h2 {
            margin: 0;
            font-size: 22px;
            color: #333;
        }
        .weather-details {
            margin-top: 20px;
        }
        .detail {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
        .detail:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: bold;
        }
        .value {
            color: #555;
        }
        .weather-image {
            margin: 20px 0;
        }
        .weather-image img {
            max-width: 100%;
            border-radius: 10px;
            width: 250px;
        }
        footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Hava Durumu Uygulaması</h1>
        </header>
        <main>
            <div class="search-section">
                <form method="POST">
                    <input type="text" name="city" placeholder="Şehir adı giriniz." required />
                    <button type="submit">Ara</button>
                </form>
            </div>
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $city = htmlspecialchars($_POST['city']);
                $apiUrl = "http://localhost/apihava.php?sehir=" . urlencode($city);

                $response = file_get_contents($apiUrl);

                if ($response === FALSE) {
                    echo "<p>API bağlantısı başarısız.</p>";
                } else {
                    $data = json_decode($response, true);

                    if (isset($data['sehir'])) {
                        $weatherCondition = strtolower($data['durum']);

                        // Hava durumu resimlerini belirlemek için dizi kullanımı
                        $images = [
                            "açık" => 'images/sun.png',
                            "parçalı bulutlu" => 'images/noto-v1--sun-behind-large-cloud.svg',
                            "çok bulutlu" => 'images/cloudy.png',
                            "hafif yağmurlu" => 'images/thunderstorm.png',
                            "az bulutlu" => 'images/partly-cloudy.png',
                            "puslu" => 'images/foggy.png',
                            "hafif sağanak yağışlı" => 'images/hafify.png'
                        ];

                        $imageSrc = $images[$weatherCondition] ?? 'images/air-hot-balloon.png';

                        echo '<div class="weather-card">';
                        echo '<div class="weather-header">';
                        echo '<h2>Şehir: ' . htmlspecialchars($data['sehir']) . '</h2>';
                        echo '</div>';
                        echo '<div class="weather-image">';
                        echo '<img src="' . htmlspecialchars($imageSrc) . '" alt="Hava Durumu Resmi">';
                        echo '</div>';
                        echo '<div class="weather-details">';
                        echo '<div class="detail"><span class="label">Sıcaklık:</span><span class="value">' . htmlspecialchars($data['sıcaklık']) . '</span></div>';
                        echo '<div class="detail"><span class="label">Durum:</span><span class="value">' . htmlspecialchars($data['durum']) . '</span></div>';
                        echo '<div class="detail"><span class="label">Nem Oranı:</span><span class="value">' . htmlspecialchars($data['nem_oranı']) . '</span></div>';
                        echo '<div class="detail"><span class="label">Rüzgar Hızı:</span><span class="value">' . htmlspecialchars($data['rüzgar_hızı']) . '</span></div>';
                        echo '<div class="detail"><span class="label">Son Güncelleme:</span><span class="value">' . htmlspecialchars($data['son_güncelleme']) . '</span></div>';
                        echo '</div>';
                        echo '</div>';
                    } else {
                        echo "<p>Şehir bulunamadı veya API hatası.</p>";
                    }
                }
            }
            ?>
        </main>
        <footer>
            <p>© 2024 Hava Durumu Uygulaması</p>
        </footer>
    </div>
</body>
<script>
if ( window.history.replaceState ) {
window.history.replaceState( null, null, window.location.href );
}
</script>
</html>
