<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\CheckTicketStatus;

// 1. Home Page
Route::get('/', function () {
    return view('welcome');
});

// 2. Public Authentication Routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');

// 3. Public Ticket Routes (For Customers)
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show')->scoped(['ticket' => 'ref']);

// 4. PROTECTED ROUTES (Agent Only)
Route::middleware(['auth'])->group(function () {
    
    // The main tickets table is now protected!
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    
    // Logout is protected because you must be logged in to log out
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Nested Protection: Auth + Ticket Status Check
    Route::middleware([CheckTicketStatus::class])->group(function () {
        Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit')->scoped(['ticket' => 'ref']);
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update')->scoped(['ticket' => 'ref']);
    });

    // Handle ticket deletion if needed
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy')->scoped(['ticket' => 'ref']);
});