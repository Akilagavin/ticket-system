<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

// Home page
Route::get('/', function () {
    return view('welcome');
});

// 2. Custom Search Route (Must be ABOVE resource routes)
// This allows customers to find their ticket using the SHA1 'ref' string
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');

// 3. Resource Routes for Tickets
// Using 'scoped' tells Laravel to use the 'ref' column for URL parameters
Route::resource('tickets', TicketController::class)->scoped([
    'ticket' => 'ref',
]);