<?php

$sehir = isset($_GET["sehir"]) ? htmlspecialchars($_GET["sehir"]) : '';

if (empty($sehir)) {
    echo json_encode(['error' => 'Şehir parametresi eksik.']);
    exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.trthaber.com/hava-durumu.html');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['search_city_name' => $sehir]));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Timeout ekleyerek cURL işlemlerini daha güvenli hale getiririz.
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'Accept-Language: tr-TR,tr;q=0.9,en-US;q=0.8,en;q=0.7',
    'Cache-Control: no-cache',
    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36'
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(['error' => 'cURL hatası: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

if (empty($response)) {
    echo json_encode(['error' => 'cURL yanıtı alınamadı.']);
    exit;
}

libxml_use_internal_errors(true);
$dom = new DOMDocument;

if (!$dom->loadHTML(mb_convert_encoding($response, 'HTML-ENTITIES', 'UTF-8'))) {
    echo json_encode(['error' => 'HTML içeriği yüklenemedi.']);
    libxml_clear_errors();
    exit;
}
libxml_clear_errors();

$xpath = new DOMXPath($dom);
$nodes = $xpath->query("//div[contains(@class, 'tab-pane') and contains(@class, 'active')]");

if ($nodes->length === 0) {
    echo json_encode(['error' => 'İlgili elemanlar bulunamadı.']);
    exit;
}

$texts = [];
foreach ($nodes as $node) {
    $textNodes = $xpath->query('.//text()', $node);
    foreach ($textNodes as $t) {
        $trimmedText = trim($t->nodeValue);
        if (!empty($trimmedText)) {
            $texts[] = $trimmedText;
        }
    }
}

$textyen = str_replace("Son güncellenme : ", "", $texts[7] ?? '');

$data = [
    'sehir' => $sehir,
    'sıcaklık' => $texts[1] ?? 'N/A',
    'durum' => $texts[2] ?? 'N/A',
    'nem_oranı' => isset($texts[4]) ? str_replace("\/", "/", $texts[4]) : 'N/A',
    'rüzgar_hızı' => isset($texts[6]) ? str_replace("\/", "/", $texts[6]) : 'N/A',
    'son_güncelleme' => $textyen ?: 'N/A',
];

header('Content-Type: application/json; charset=UTF-8');
echo json_encode($data, JSON_UNESCAPED_UNICODE);

?>
