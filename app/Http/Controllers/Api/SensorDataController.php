<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSensorReadingRequest;
use App\Models\SensorReadings;
use Illuminate\Http\JsonResponse;

class SensorDataController extends Controller
{
    public function store(StoreSensorReadingRequest $request): JsonResponse
    {
        $reading = SensorReadings::create($request->validated());

        return response()->json([
            'message' => 'Sensor reading recorded.',
            'id' => $reading->id,
        ], 201);
    }
}
