<?php

namespace App\Services;

use App\Exceptions\OpenWeatherRequestException;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class OpenWeatherService
{
    /**
     * check weather
     *
     * @param string $city
     * @return array
     */
    public function check(string $city)
    {
        $url = config('weather.url') . '/weather';

        $weather = $this->get($url, $this->generateParams($city));

        return [
            'description' => ucwords($weather['weather'][0]['description']) . '.',
            'icon' => 'https://openweathermap.org/img/wn/' . $weather['weather'][0]['icon'] . '@2x.png',
            'feels_like' => round($weather['main']['feels_like']),
            'date_time' => (new Carbon())->format('D, M j, h:i A')
        ];
    }

    /**
     * Get weather forecast
     *
     * @param string $city
     * @return Collection
     */
    public function getForecast(string $city)
    {
        $url = config('weather.url') . '/forecast';

        $weather = $this->get($url, $this->generateParams($city));

        return collect($weather['list'])->map(function ($i) {
            return [
                'date_time' => (new Carbon($i['dt']))->format('M j, h:i A'),
                'icon' => 'https://openweathermap.org/img/wn/' . $i['weather'][0]['icon'] . '@2x.png',
                'description' => ucwords($i['weather'][0]['description']) . '.',
                'temp_min' => round($i['main']['temp_min']),
                'temp_max' => round($i['main']['temp_max']),
            ];
        });
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
            'appid' => config('weather.api_key'),
            'units' => 'metric',
        ];
    }

    /**
     * Send get request
     *
     * @param array $params
     * @return array
     * @throws OpenWeatherRequestException
     */
    protected function get(string $url, array $params) : array
    {
        try {
            $response = Http::get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            throw new OpenWeatherRequestException();
        } catch (RequestException $e) {
            throw new OpenWeatherRequestException($e);
        } catch (ConnectionException $e) {
            throw new OpenWeatherRequestException($e);
        }
    }
}
