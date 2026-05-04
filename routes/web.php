<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LoginController; // Import the LoginController
use App\Http\Middleware\CheckTicketStatus;

// 1. Home Page
Route::get('/', function () {
    return view('welcome');
});

// 2. Authentication Routes
// These handle logging in agents/admins and logging them out.
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// 3. Custom Search Route
// This allows customers to find their ticket using the SHA1 'ref' string.
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');

// 4. Protected Routes
// Middleware is applied here to prevent editing tickets that are already "Closed".
Route::middleware([CheckTicketStatus::class])->group(function () {
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
});

// 5. Resource Routes for Tickets
Route::resource('tickets', TicketController::class)->except([
    'edit', 'update'
])->scoped([
    'ticket' => 'ref',
]);