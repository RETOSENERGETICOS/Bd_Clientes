<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DistributionController extends Controller
{
    public function index(Request $request): JsonResponse {
        return response()->json(
            Distribution::all()->map( fn(Distribution $distribution) => $this->showDistribution($distribution) )
        );
    }

    private function showDistribution(Distribution $distribution): array {
        return [
            'id' => $distribution->id,
            'name' => $distribution->name
        ];
    }
}
