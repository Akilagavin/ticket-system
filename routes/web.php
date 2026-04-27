<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// 1. Landing Page
Route::get('/', function () {
    return view('welcome');
});

// 2. Search Route 
// IMPORTANT: This must be ABOVE the resource route so "search" isn't treated as an ID
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');

// 3. Resource Routes
// 'scoped' tells Laravel to look up the Ticket by the 'ref' column in the database
Route::resource('tickets', TicketController::class)->scoped([
    'ticket' => 'ref',
]);

// 3. Resource Routes for Tickets

Route::resource('tickets', TicketController::class)->scoped([
    'ticket' => 'ref',
]);