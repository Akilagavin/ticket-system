<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

// 1. Landing Page
Route::get('/', function () {
    return view('welcome');
});

<<<<<<< HEAD
// 2. Search Route 
// IMPORTANT: This must be ABOVE the resource route so "search" isn't treated as an ID
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');

// 3. Resource Routes
// 'scoped' tells Laravel to look up the Ticket by the 'ref' column in the database
Route::resource('tickets', TicketController::class)->scoped([
    'ticket' => 'ref',
]);

// 3. Resource Routes for Tickets
// This automatically creates routes for index, create, store, show, etc.
// We use 'scoped' so the URL uses the 'ref' instead of the numeric 'id'
=======
// 2. Custom Search Route
// Using GET for search is standard so users can bookmark their results page
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');

// 3. Resource Routes for Tickets
// We use 'scoped' so Laravel knows to look for the 'ref' column in the database
// instead of the default 'id' when visiting /tickets/{ticket}
>>>>>>> feature/add-ticket-controller
Route::resource('tickets', TicketController::class)->scoped([
    'ticket' => 'ref',
]);