<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Turn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TurnController extends Controller
{
    public function index(Request $request): JsonResponse {
        return response()->json(
            Turn::all()->map( fn(Turn $turn) => $this->showTurn($turn) )
        );
    }

    private function showTurn(Turn $turn): array {
        return [
            'id' => $turn->id,
            'name' => $turn->name
        ];
    }
}
