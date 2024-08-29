<?php
$url = 'https://www.trthaber.com/istanbul-hava-durumu.html';
$htmlContent = file_get_contents($url);

if ($htmlContent === false) {
    die('Sayfa indirilemedi.');
}

$dom = new DOMDocument;
libxml_use_internal_errors(true);
$dom->loadHTML($htmlContent);
libxml_clear_errors();

$xpath = new DOMXPath($dom);
$query = '//div[contains(@class, "weather-status-container")]';
$entries = $xpath->query($query);

foreach ($dom->getElementsByTagName('a') as $link) {
    $link->removeAttribute('href');
}

if ($entries->length > 0) {
    echo $dom->saveHTML($entries->item(0));
} else {
    echo 'Veri bulunamadı.';
}
?>