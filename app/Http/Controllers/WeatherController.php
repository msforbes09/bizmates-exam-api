<?php

namespace App\Http\Controllers;

use App\Exceptions\OpenWeatherRequestException;
use App\Http\Requests\WeatherCheckRequest;
use App\Services\OpenWeatherService;
use Illuminate\Http\JsonResponse;

class WeatherController extends Controller
{
    /**
     * @var OpenWeatherService $service
     */
    protected $service;

    /**
     * WeatherController constructor
     *
     * @param OpenWeatherService $service
     */
    public function __construct(OpenWeatherService $service)
    {
        $this->service = $service;
    }

    /**
     * Check weather
     *
     * @param WeatherCheckRequest $request
     * @return JsonResponse
     */
    public function check(WeatherCheckRequest $request)
    {
        try {
            $weather = $this->service->check($request->input('city'));

            return response()->json(['data' => $weather], 200);
        } catch (OpenWeatherRequestException $th) {
            return response()->json(['message' => 'Sorry! Weather check not available right now!'], 400);
        }
    }

    /**
     * Weather forecast
     *
     * @param WeatherCheckRequest $request
     * @return JsonResponse
     */
    public function forecast(WeatherCheckRequest $request)
    {
        try {
            $weather = $this->service->getForecast($request->input('city'));

            return response()->json(['data' => $weather], 200);
        } catch (OpenWeatherRequestException $th) {
            return response()->json(['message' => 'Sorry! Weather check not available right now!'], 400);
        }
    }
}
