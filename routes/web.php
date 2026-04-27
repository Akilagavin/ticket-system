<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

// Home page
Route::get('/', function () {
    return view('welcome');
});

// 2. Custom Search Route
// Using GET for search is standard so users can bookmark their results page
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');

// 3. Resource Routes for Tickets
// We use 'scoped' so Laravel knows to look for the 'ref' column in the database
// instead of the default 'id' when visiting /tickets/{ticket}
Route::resource('tickets', TicketController::class)->scoped([
    'ticket' => 'ref',
]);