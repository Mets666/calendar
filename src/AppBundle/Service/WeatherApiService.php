<?php


namespace AppBundle\Service;


class WeatherApiService
{

    protected $key;
    protected $url;
    protected $cacheAdapter;

    public function __construct($key, $url, $guzzleClient, $cacheAdapter, $geoIpService)
    {
        $this->key = $key;
        $this->url = $url;
        $this->guzzleClient = $guzzleClient;
        $this->geoIpService = $geoIpService;
        $this->chacheAdapter = $cacheAdapter;
    }

    public function getByIp($ip)
    {
        $location = $this->geoIpService->getCity($ip);

        $res = $this->guzzleClient->request('GET', 'api.openweathermap.org/data/2.5/weather?q='.$location.'&APPID='.$this->key);
        $weatherData = json_decode($res->getBody()->getContents(), true);

        $weather['city'] = $weatherData['name'];
        $weather['country_code'] = $weatherData['sys']['country'];
        $weather['temperature'] = $this->k_to_c($weatherData['main']['temp']);
        $weather['humidity'] = $weatherData['main']['humidity'];
        $weather['description'] = $weatherData['weather'][0]['description'];

        return $weather;
//        return $weatherData;
    }

    function k_to_c($temp) {
        if ( !is_numeric($temp) ) { return false; }
        return round(($temp - 273.15));
    }
}