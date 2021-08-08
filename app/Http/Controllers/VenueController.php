<?php

namespace App\Http\Controllers;

use App\Exceptions\FourSquareRequestException;
use App\Http\Requests\VenueSearchRequest;
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

    /**
     * Search venues
     *
     * @param VenueSearchRequest $request
     * @return JsonResponse
     */
    public function search(VenueSearchRequest $request)
    {
        try {
            $venues = $this->service->search($request);

            return response()->json(['data' => $venues], 200);
        } catch (FourSquareRequestException $e) {
            return response()->json(['message' => 'Sorry! Venue search not available right now!'], 400);
        }
    }

    /**
     * Show details
     *
     * @param mixed $id
     * @return JsonResponse
     */
    public function details($id)
    {
        try {
            $details = $this->service->getDetails($id);

            return response()->json(['data' => $details], 200);
        } catch (FourSquareRequestException $e) {
            return response()->json(['message' => 'Sorry! Venue search not available right now!'], 400);
        }
    }
}
