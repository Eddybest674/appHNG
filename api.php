<?php
function getGeolocation($ip) {
    $apiKey = '81ba506d46eb2d'; 
    $url = "http://ipinfo.io/{$ip}/json?token={$apiKey}";
    $json = file_get_contents($url);
    if ($json === false) {
        return null; 
    }
    return json_decode($json, true);
}


function getWeather($city) {
    $apiKey = '6e93219f9c704757b8bf258344fb4f18'; 
    $encodedCity = urlencode($city);
    $url = "http://api.openweathermap.org/data/2.5/weather?q={$encodedCity}&units=metric&appid={$apiKey}";
    
    
    echo $url;
    
    $json = file_get_contents($url);
    if ($json === false) {
        return null; 
    }
    return json_decode($json, true);
}

$visitorName = isset($_GET['visitor_name']) ? $_GET['visitor_name'] : 'Visitor';


$clientIp = $_SERVER['REMOTE_ADDR'];

if ($clientIp === '::1' || $clientIp === '127.0.0.1') {
    $clientIp = '8.8.8.8'; 
}


$geoData = getGeolocation($clientIp);
$city = isset($geoData['city']) ? $geoData['city'] : 'Unknown';


$weatherData = null;

if ($city !== 'Unknown') {
    $weatherData = getWeather($city);
    if ($weatherData && isset($weatherData['main']['temp'])) {
        $temperature = $weatherData['main']['temp'];
    } else {
        $temperature = 'unknown'; 
    }
} else {
    $temperature = 'unknown';
}


$response = array(
    'client_ip' => $clientIp,
    'location' => $city,
    'greeting' => "Hello, $visitorName!, the temperature is $temperature degrees Celsius in $city"
);


header('Content-Type: application/json');

echo json_encode($response);
?>
