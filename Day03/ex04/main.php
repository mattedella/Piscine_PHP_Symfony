<?php
require_once __DIR__ . '/src/Weather.php';

use Matteo\Weather\Weather;

$city = 'Florence'; // Replace with your city
$latitude = 43.769224;
$longitude = 11.255869;

try {
    $weather = new Weather($city, $latitude, $longitude);
    $temp = $weather->getCurrentWeatherCelsius();
    echo "Current temperature in $city: $temp °C\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
