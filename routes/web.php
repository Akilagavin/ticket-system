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




// 3. Resource Routes for Tickets
// This automatically creates routes for index, create, store, show, etc.
// We use 'scoped' so the URL uses the 'ref' instead of the numeric 'id'
Route::resource('tickets', TicketController::class)->scoped([
    'ticket' => 'ref',
]);