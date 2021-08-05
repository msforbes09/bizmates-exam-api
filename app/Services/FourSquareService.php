<?php

namespace App\Services;

use App\Exceptions\FourSquareRequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class FourSquareService
{
    /**
     * Get categories
     *
     * @return Collection
     */
    public function getCategories()
    {
        $params = $this->generateParams();
        $url = config('venue.url') . '/categories';

        $response = $this->get($url, $params);

        return collect($response['response']['categories'])->map(function ($q) {
            return [
                'id' => $q['id'],
                'name' => $q['name'],
                'icon' => $q['icon']['prefix'] . '64' . $q['icon']['suffix'],
            ];
        });
    }

    /**
     * Generate params
     *
     * @return array
     */
    protected function generateParams() : array
    {
        return [
            'client_id' => config('venue.app_id'),
            'client_secret' => config('venue.app_secret'),
            'v' => date('Ymd'),
        ];
    }

    /**
     * get.
     *
     * @param string $url
     * @param array  $params
     * @return array
     * @throws FourSquareRequestException
     */
    protected function get(string $url, array $params) : array
    {
        $response = Http::get($url, $params);

        if ($response->successful()) {
            return $response->json();
        }

        throw new FourSquareRequestException();
    }
}
