<?php
// api.php
header('Content-Type: application/json');

// Helper: curl GET
function curl_get($url, $headers = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $out = curl_exec($ch);
    curl_close($ch);
    return $out;
}

// Route request
$endpoint = $_GET['endpoint'] ?? '';

switch ($endpoint) {
    // مواقيت الصلاة
    case 'prayer-times':
        $city = $_GET['city'] ?? 'Cairo';
        $country = $_GET['country'] ?? 'EG';
        $url = "http://api.aladhan.com/v1/timingsByCity?city={$city}&country={$country}&method=8";
        echo curl_get($url);
        break;
    // القرآن الصوتي
    case 'quran-audio':
        $surah = $_GET['surah'] ?? 1;
        $ayah = $_GET['ayah'] ?? 1;
        $url = "https://api.quran.com/api/v4/quran/verses/audio?verse_key={$surah}:{$ayah}";
        echo curl_get($url);
        break;
    // اتجاه القبلة
    case 'qibla':
        $lat = $_GET['lat'];
        $lon = $_GET['lon'];
        $apiKey = 'YOUR_QIBLA_API_KEY';
        $url = "https://zylalabs.com/api/945/qibla+direction+api/764/get+direction?latitude={$lat}&longitude={$lon}";
        $headers = ["Authorization: Bearer $apiKey"];
        echo curl_get($url, $headers);
        break;
    // التقويم الهجري
    case 'hijri-calendar':
        $date = $_GET['date'] ?? date('Y-m-d');
        $url = "https://api.aladhan.com/v1/gToH?date={$date}";
        echo curl_get($url);
        break;
    // بحث عن مطاعم ومساجد حلال (مثال باستخدام Foursquare)
    case 'halal-places':
        $lat = $_GET['lat'];
        $lon = $_GET['lon'];
        $apikey = "YOUR_FOURSQUARE_API_KEY";
        $url = "https://api.foursquare.com/v2/venues/search?ll={$lat},{$lon}&categoryId=4bf58dd8d48988d10f941735&oauth_token={$apikey}&v=20250101";
        echo curl_get($url);
        break;
    // حساب الزكاة (من الفرونت أو الداخلية)
    case 'zakat':
        $income = $_GET['income'];
        $gold_price = $_GET['gold_price'];
        $nisab = $gold_price * 85;
        $zakat = 0;
        if($income > $nisab){
          $zakat = $income * 0.025;
        }
        echo json_encode(['zakat' => $zakat]);
        break;
    //... أضف مزيد من الـ endpoints حسب الحاجة
    default:
        echo json_encode(['error' => 'Unknown endpoint']);
}

?>
