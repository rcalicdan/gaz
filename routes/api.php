<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Routing\RouteDataController;

Route::middleware(['auth:api'])->group(function () {

    Route::prefix('route-data')->group(function () {
        Route::get('/drivers', [RouteDataController::class, 'getDrivers']);
        Route::get('/orders', [RouteDataController::class, 'getOrdersForDriverAndDate']);
        Route::get('/all-orders', [RouteDataController::class, 'getAllOrdersForDateRange']);
        Route::get('/statistics', [RouteDataController::class, 'getRouteStatistics']);
        Route::post('/geocode', [RouteDataController::class, 'triggerGeocoding']);
        Route::post('/save-optimization', [RouteDataController::class, 'saveRouteOptimization']);
        Route::get('/saved-optimization', [RouteDataController::class, 'getSavedRouteOptimization']);
        Route::get('/driver-optimizations', [RouteDataController::class, 'getMyRouteOptimizations']);
    });

    Route::post('vroom/optimize', [RouteDataController::class, 'optimize']);
});