<?php

namespace App\Services;

use App\Exceptions\FourSquareRequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;
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

        return collect($response['response']['categories'])->map(function ($category) {
            return [
                'id' => $category['id'],
                'name' => $category['name'],
                'icon' => $category['icon']['prefix'] . '64' . $category['icon']['suffix'],
                'sub_categories' => collect($category['categories'])->map(function ($subCategory) {
                    return [
                        'id' => $subCategory['id'],
                        'name' => $subCategory['name'],
                        'icon' => $subCategory['icon']['prefix'] . '64' . $subCategory['icon']['suffix'],
                    ];
                })
            ];
        });
    }

    /**
     * Search
     *
     * @param Request $data
     * @return Collection
     */
    public function search(Request $data)
    {
        $params = $this->generateParams();
        $params['near'] = $data->get('city') . ',JP';
        $params['categoryId'] = $data->get('category');
        $params['query'] = $data->get('q');
        $params['limit'] = 50;

        $url = config('venue.url') . '/search';

        $response = $this->get($url, $params);

        return collect($response['response']['venues'])->map(function ($venue) {
            return [
                'id' => $venue['id'],
                'name' => $venue['name'],
                'address' => implode(' ', $venue['location']['formattedAddress']),
                'lat' => $venue['location']['lat'],
                'lng' => $venue['location']['lng'],
                'category_image' => $venue['categories'][0] ? ($venue['categories'][0]['icon']['prefix'] . '64' . $venue['categories'][0]['icon']['suffix']) : null,
            ];
        });
    }

    /**
     * Get details
     *
     * @param string $venueId
     * @return array
     */
    public function getDetails(string $venueId)
    {
        $params = $this->generateParams();
        $url = config('venue.url') . '/' . $venueId;

        $response = $this->get($url, $params);

        $details = $response['response']['venue'];

        return [
            'name' => $details['name'],
            'address' => implode(' ', $details['location']['formattedAddress']),
            'website' => $details['url'],
            'likes' => $details['likes']['count'],
            'photo' => $details['bestPhoto'] ?
                (
                    $details['bestPhoto']['prefix'] .
                    $details['bestPhoto']['width'] . 'x' .
                    $details['bestPhoto']['height'] .
                    $details['bestPhoto']['suffix']
                ) : null,
            'contacts' => collect($details['contact'])
                ->only(['formattedPhone', 'twitter', 'instagram', 'facebook'])
                ->map(function ($val, $key) {
                    if ($key == 'facebook') {
                        return 'https://www.facebook.com/' . $val;
                    }

                    if ($key == 'twitter') {
                        return 'https://www.twitter.com/' . $val;
                    }

                    if ($key == 'instagram') {
                        return 'https://www.instagram.com/' . $val;
                    }

                    return $val;
                })
        ];
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
    protected function get(string $url, array $params)
    {
        try {
            $response = Http::withHeaders(['Accept-Language' => 'en'])->get($url, $params);

            if ($response->successful()) {
                return $response->json();
            }

            throw new FourSquareRequestException();
        } catch (RequestException $e) {
            throw new FourSquareRequestException($e);
        } catch (ConnectionException $e) {
            throw new FourSquareRequestException($e);
        }
    }
}
