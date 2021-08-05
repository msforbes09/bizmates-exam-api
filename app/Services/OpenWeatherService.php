<?php

namespace App\Services;

use App\Exceptions\OpenWeatherRequestException;
use Illuminate\Support\Facades\Http;

class OpenWeatherService
{
    /**
     * check weather
     *
     * @param string $city
     * @return array
     */
    public function check(string $city) : array
    {
        return $this->get($this->generateParams($city));
    }

    /**
     * Generate params
     *
     * @param string $q
     * @return array
     */
    protected function generateParams(string $q) : array
    {
        return [
            'q' => $q . ',JP',
            'appid' => config('weather.api_key')
        ];
    }

    /**
     * Send get request
     *
     * @param array $params
     * @return array
     * @throws OpenWeatherRequestException
     */
    protected function get(array $params) : array
    {
        $host = config('weather.url');

        $response = Http::get($host, $params);

        if ($response->successful()) {
            return $response->json();
        }

        throw new OpenWeatherRequestException();
    }
}