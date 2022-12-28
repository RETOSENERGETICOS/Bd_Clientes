<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Services;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServicesController extends Controller
{
    public function index(Request $request): JsonResponse {
        return response()->json(
            Services::all()->map( fn(Services $services) => $this->showServices($services) )
        );
    }

    private function showServices(Services $services): array {
        return [
            'id' => $services->id,
            'name' => $services->name
        ];
    }
}
