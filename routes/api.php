<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Protect these routes to ensure JSON responses for API
Route::middleware('api')->group(function () {
    Route::get('/user', function (Request $request) {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'You must be authenticated to access this resource.'
            ], 401);
        }
        return response()->json($request->user());
    })->middleware('auth:sanctum');
});

// Ticket System API - Version 1
Route::group([
    'prefix' => 'v1',
    'middleware' => 'api',
], function() {
    
    /**
     * Ticket Endpoints
     */
    
    // GET /api/v1/tickets - Get all tickets with pagination
    Route::get('/tickets', [TicketController::class, 'index']);
    
    // POST /api/v1/tickets - Create a new ticket
    Route::post('/tickets', [TicketController::class, 'store']);
    
    // GET /api/v1/tickets/{id} - Get single ticket by ID
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
    
    // PATCH/PUT /api/v1/tickets/{id} - Update ticket
    Route::match(['patch', 'put'], '/tickets/{id}', [TicketController::class, 'update']);
    
    // DELETE /api/v1/tickets/{id} - Delete ticket
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy']);
    
});