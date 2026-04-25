<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\KpoDocumentController;
use App\Http\Controllers\Api\Routing\RouteDataController;
use App\Http\Controllers\Api\ClientApp\ClientAuthController;
use App\Http\Controllers\Api\ClientApp\ClientProfileController;
use App\Http\Controllers\Api\ClientApp\ClientWasteTypeController;
use App\Http\Controllers\Api\ClientApp\ClientOrderController;
use App\Http\Controllers\Api\ClientApp\ClientInvoiceController;
use App\Http\Controllers\Api\ClientApp\ClientKpoController;
use App\Http\Controllers\Api\ClientApp\ClientSupportController;
use App\Http\Controllers\Api\ClientApp\ClientWasteRecordController;

Route::prefix('client-app')->group(function () {

    Route::prefix('auth')->controller(ClientAuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login', 'login');
    });

    Route::middleware('auth:api')->group(function () {

        Route::post('/auth/logout', [ClientAuthController::class, 'logout']);

        Route::prefix('profile')->controller(ClientProfileController::class)->group(function () {
            Route::get('/', 'show');
            Route::put('/', 'update');
        });

        Route::get('/waste-types', [ClientWasteTypeController::class, 'index']);

        Route::prefix('orders')->controller(ClientOrderController::class)->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store');
            Route::get('/{id}', 'show');
            Route::put('/{id}/cancel', 'cancel');
        });

        Route::prefix('invoices')->controller(ClientInvoiceController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::get('/{id}/download', 'download');
        });

        Route::prefix('documents/kpo')->controller(ClientKpoController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::get('/{id}/download', 'download');
        });

           Route::prefix('waste')->controller(ClientWasteRecordController::class)->group(function () {
            Route::get('/records', 'index');
            Route::get('/records/export', 'export');
            Route::get('/statistics', 'statistics');
        });

        Route::prefix('support')->controller(ClientSupportController::class)->group(function () {
            Route::get('/contact-info', 'contactInfo');
            Route::post('/messages', 'sendMessage');
        });
    });
});

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
        Route::get('/', [KpoDocumentController::class, 'index']);
        Route::get('/{kpoDocument}', [KpoDocumentController::class, 'show']);
        Route::post('/generate-for-pickup/{pickup}', [KpoDocumentController::class, 'generatePdfForPickup']);
        Route::post('/generate-my-pickup', [KpoDocumentController::class, 'generatePdfForMyPickup']);
        Route::post('/{kpoDocument}/generate-pdf', [KpoDocumentController::class, 'generatePdf']);
        Route::get('/pickup/{pickup}/download', [KpoDocumentController::class, 'downloadPdfByPickup']);
        Route::get('/pickup/{pickup}/preview', [KpoDocumentController::class, 'previewPdfByPickup']);
        Route::get('/{kpoDocument}/download', [KpoDocumentController::class, 'downloadPdf']);
        Route::get('/{kpoDocument}/preview', [KpoDocumentController::class, 'previewPdf']);
        Route::post('/{kpoDocument}/email-to-client', [KpoDocumentController::class, 'emailToClient']);
        Route::post('/{kpoDocument}/email-to-custom', [KpoDocumentController::class, 'emailToCustomAddress']);
        Route::get('/{kpoDocument}/email-history', [KpoDocumentController::class, 'emailHistory']);
        Route::get('/{kpoDocument}/email-statistics', [KpoDocumentController::class, 'emailStatistics']);
        Route::post('/email-logs/{emailLog}/retry', [KpoDocumentController::class, 'retryEmail']);
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
        Route::delete('/delete-optimization', [RouteDataController::class, 'deleteSavedRouteOptimization']);
    });

    Route::prefix('invoices')->group(function () {
        Route::post('/generate-for-pickup/{pickup}', [InvoiceController::class, 'generateForPickup']);
    });

    Route::post('vroom/optimize', [RouteDataController::class, 'optimize']);
});
