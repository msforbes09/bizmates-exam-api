<?php

namespace App\Http\Controllers;

use App\Exceptions\OpenWeatherRequestException;
use App\Http\Requests\WeatherCheckRequest;
use App\Services\OpenWeatherService;
use Illuminate\Http\JsonResponse;

class WeatherController extends Controller
{
    /**
     * Check weather
     *
     * @param WeatherCheckRequest $request
     * @return JsonResponse
     */
    public function check(WeatherCheckRequest $request)
    {
        try {
            $weather = (new OpenWeatherService)->check($request->input('city'));

            return response()->json(['data' => $weather], 200);
        } catch (OpenWeatherRequestException $th) {
            return response()->json(['message' => 'Sorry! Weather check not available right now!'], 400);
        }
    }
}
