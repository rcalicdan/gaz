<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\KpoDocumentController;
use App\Http\Controllers\Api\Routing\RouteDataController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/check-email', [AuthController::class, 'checkEmail']);
});

Route::middleware(['auth:api'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('/user', [AuthController::class, 'getUser']);
        Route::put('/user', [AuthController::class, 'updateUser']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::prefix('kpo-documents')->group(function () {
        Route::post('/{kpoDocument}/generate-pdf', [KpoDocumentController::class, 'generatePdf']);
        Route::get('/{kpoDocument}/download', [KpoDocumentController::class, 'downloadPdf']);
        Route::get('/{kpoDocument}/preview', [KpoDocumentController::class, 'previewPdf']);
    });

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
