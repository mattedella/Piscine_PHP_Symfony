<?php
namespace Matteo\Weather;

require_once __DIR__ . '/../vendor/autoload.php';

use RestClient;

class Weather
{
    private $latitude;
    private $longitude;
    private $city;
    private $client;

    public function __construct($city, $latitude, $longitude)
    {
        $this->city = $city;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->client = new \RestClient([
            'base_url' => 'https://api.open-meteo.com/v1/'
        ]);
    }

    public function getCurrentWeatherCelsius()
    {
        $params = [
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'current_weather' => 'true'
        ];
        $result = $this->client->get('forecast', $params);
        $data = $result->response;
        if (is_string($data)) {
            $data = json_decode($data, true); // decode as array
            $temp = isset($data['current_weather']['temperature']) ? $data['current_weather']['temperature'] : null;
        } elseif (is_object($data)) {
            $temp = isset($data->current_weather->temperature) ? $data->current_weather->temperature : null;
        } else {
            $temp = null;
        }
        if ($temp === null) {
            if (isset($result->response)) {
                echo "API response: " . $result->response . "\n";
            }
            throw new \Exception('Failed to fetch weather data');
        }
        file_put_contents(__DIR__ . '/../weather.txt', "{$this->city}: {$temp}°C\n");
        return $temp;
    }
}
