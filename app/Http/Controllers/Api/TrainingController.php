<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request): JsonResponse {
        return response()->json(
            Training::all()->map( fn(Training $training) => $this->showTraining($training) )
        );
    }

    private function showTraining(Training $training): array {
        return [
            'id' => $training->id,
            'name' => $training->name
        ];
    }
}
