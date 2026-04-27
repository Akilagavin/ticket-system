<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Middleware\CheckTicketStatus;

// 1. Home page
Route::get('/', function () {
    return view('welcome');
});

// 2. Custom Search Route
// Allows customers to find their ticket using the unique 'ref' string
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');

// 3. Protected Routes (Middleware applied)
// We define these before the resource so they take priority
Route::middleware([CheckTicketStatus::class])->group(function () {
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
});

// 4. Resource Routes for Tickets
// We use 'except' to prevent duplicate definitions of edit/update
// We use 'scoped' so the URL uses the 'ref' instead of the numeric 'id'
Route::resource('tickets', TicketController::class)->except([
    'edit', 'update'
])->scoped([
    'ticket' => 'ref',
]);