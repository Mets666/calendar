<?php


namespace AppBundle\Service;


use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class WeatherApiService
{

    protected $key;
    protected $url;
    protected $cacheAdapter;
    protected $guzzleClient;
    protected $geoIpService;

    public function __construct($key, $url, FilesystemAdapter $cacheAdapter, Client $guzzleClient, GeoIpService $geoIpService)
    {
        $this->key = $key;
        $this->url = $url;

        $this->cacheAdapter = $cacheAdapter;
        $this->guzzleClient = $guzzleClient;
        $this->geoIpService = $geoIpService;
    }

    public function getByIp($ip)
    {
        $location = $this->geoIpService->getCity($ip);
        $this->cacheAdapter->deleteItem($location);

        $cachedData = $this->cacheAdapter->getItem($location);
        
        if (!$cachedData->isHit()) {
            $weather = $this->getAndParseWeather($location);
            $cachedData->set($weather);
            $cachedData->expiresAfter(1800);
            $this->cacheAdapter->save($cachedData);
        }
        else{
            $weather = $cachedData->get();
        }

        return $weather;
    }

    function k_to_c($temp) {
        if ( !is_numeric($temp) ) { return false; }
        return round(($temp - 273.15));
    }

    public function getAndParseWeather($location)
    {
        try {
            $res = $this->guzzleClient->request('GET', $this->url . '?q=' . $location . '&APPID=' . $this->key);
            $weatherData = json_decode($res->getBody()->getContents(), true);

            $weather['city'] = $weatherData['name'];
            $weather['country_code'] = $weatherData['sys']['country'];
            $weather['temperature'] = $this->k_to_c($weatherData['main']['temp']);
            $weather['humidity'] = $weatherData['main']['humidity'];
            $weather['pressure'] = $weatherData['main']['pressure'];
            $weather['wind'] = $weatherData['wind']['speed'];
            $weather['description'] = $weatherData['weather'][0]['description'];
            $weather['image_url'] = 'http://openweathermap.org/themes/openweathermap/assets/vendor/owm/img/widgets/' . $weatherData['weather'][0]['icon'] . '.png';
        }
        catch (\Exception $e){
            return null;
        }
//        dump($weatherData); die;
        return $weather;
    }
}