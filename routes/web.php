<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

// Home page
Route::get('/', function () {
    return view('welcome');
});



// 2. Custom Search Route (Must be ABOVE resource routes)
// This allows customers to find their ticket using the 'ref' string
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');


// 3. Resource Routes for Tickets
// This automatically creates routes for index, create, store, show, etc.
// We use 'scoped' so the URL uses the 'ref' instead of the numeric 'id'
Route::resource('tickets', TicketController::class)->scoped([
    'ticket' => 'ref',
]);