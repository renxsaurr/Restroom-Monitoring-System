<?php

use App\Http\Controllers\Api\SensorDataController;
use App\Models\CleaningLogs;
use Illuminate\Support\Facades\Route;

Route::post('/sensor-data', [SensorDataController::class, 'store'])
    ->middleware('iot.key')
    ->name('api.sensor-data.store');

Route::get('/last-cleaned', function () {
    $lastCleaning = CleaningLogs::query()->latest('cleaned_at')->first();
    $timestamp = $lastCleaning?->cleaned_at?->timestamp ?? 0;

    return response((string) $timestamp, 200)
        ->header('Content-Type', 'text/plain');
})->middleware('iot.key')->name('api.last-cleaned');
