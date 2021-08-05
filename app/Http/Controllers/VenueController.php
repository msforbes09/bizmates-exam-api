<?php

namespace App\Http\Controllers;

use App\Exceptions\FourSquareRequestException;
use App\Services\FourSquareService;

class VenueController extends Controller
{
    /**
     * @var FourSquareService $service
     */
    protected $service;

    /**
     * VenueController constructor
     *
     * @param FourSquareService $service
     */
    public function __construct(FourSquareService $service)
    {
        $this->service = $service;
    }

    /**
     * Venue categories
     *
     * @return JsonResponse
     */
    public function categories()
    {
        try {
            $categories = $this->service->getCategories();

            return response()->json(['data' => $categories], 200);
        } catch (FourSquareRequestException $e) {
            return response()->json(['message' => 'Sorry! Venue categories not available right now!'], 400);
        }
    }
}
