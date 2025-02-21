<?php
namespace Kakarazi\Weather\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\HTTP\Client\Curl;

class Data extends AbstractHelper
{
    protected $curl;
    protected $apiKey = "0d57caef709e0aca85317688734e2509";

    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    public function getWeatherDataByCity($city)
    {
        $url = "https://api.openweathermap.org/data/2.5/weather?lat=21.02&lon=105.85&appid=0d57caef709e0aca85317688734e2509";

        $this->curl->get($url);
        $response = $this->curl->getBody();

        return json_decode($response, true);
    }

    public function getWeatherDataByCoordinates($longitude, $latitude)
    {
        $url = "https://api.openweathermap.org/data/2.5/weather?lat=$latitude&lon=$longitude&appid=$this->apiKey";

        $this->curl->get($url);
        $response = $this->curl->getBody();

        return json_decode($response, true);
    }

    public function getFiveDayForecastWeatherDataByCoordinates($longitude, $latitude)
    {
        $url = "https://api.openweathermap.org/data/2.5/forecast?lat=$latitude&lon=$longitude&appid=$this->apiKey";

        $this->curl->get($url);
        $response = $this->curl->getBody();

        return json_decode($response, true);
    }
}
