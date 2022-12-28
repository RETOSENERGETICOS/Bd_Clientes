<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request): JsonResponse {
        return response()->json(
            Country::all()->map( fn(Country $country) => $this->showCountry($country) )
        );
    }

    private function showCountry(Country $country): array {
        return [
            'id' => $country->id,
            'name' => $country->name
        ];
    }
}
