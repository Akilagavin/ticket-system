<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Standard Sanctum User Route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

// Ticket System API - Version 1
Route::group([
    'prefix' => 'v1',
    'middleware' => 'api',
], function() {
    
    /**
     * PUBLIC ACCESS
     * These endpoints do not require a login token.
     */
    Route::post('/tickets', [TicketController::class, 'store']); // Public

    /**
     * AUTHENTICATED ACCESS
     * These endpoints require a valid Sanctum Bearer Token.
     */
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/tickets', [TicketController::class, 'index']);     // Auth only
        Route::get('/tickets/{id}', [TicketController::class, 'show']); // Auth only
        Route::match(['patch', 'put'], '/tickets/{id}', [TicketController::class, 'update']);           // Auth only
        Route::delete('/tickets/{id}', [TicketController::class, 'destroy']);                            // Auth only
    });
});