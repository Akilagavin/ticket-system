<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

// Home page
Route::get('/', function () {
    return view('welcome');
});

// // Resource routes
// Route::resource('tickets', TicketController::class);

// // Custom search route
// Route::post('/ticket/search', [TicketController::class, 'search'])->name('tickets.search');