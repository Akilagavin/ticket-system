<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\CheckTicketStatus;

// 1. Home Page
Route::get('/', function () {
    return view('welcome');
});

// 2. Custom Search Route
// This allows customers to find their ticket using the SHA1 'ref' string.
// Must stay above the resource route to avoid being captured as an ID.
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');

// 3. Protected Routes
// Middleware is applied here to prevent editing tickets that are already "Closed".
Route::middleware([CheckTicketStatus::class])->group(function () {
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
});

// 4. Resource Routes for Tickets
// We use 'except' because edit and update are defined above with middleware.
Route::resource('tickets', TicketController::class)->except([
    'edit', 'update'
])->scoped([
    'ticket' => 'ref',
]);