<?php

namespace App\Http\Requests;

use App\Enums\CityEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WeatherCheckRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'city' => ['required', Rule::in(CityEnum::CITIES)],
        ];
    }
}
