<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\CheckTicketStatus;

// 1. PUBLIC ROUTES
Route::get('/', function () {
    return view('welcome');
});

// Authentication
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

// Customer Ticket Creation & Search
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');

/**
 * Public Ticket View
 * Scope: Uses the SHA1 'ref' column to prevent ID guessing.
 */
Route::get('/tickets/{ticket:ref}', [TicketController::class, 'show'])->name('tickets.show');

// 2. PROTECTED AGENT ROUTES
Route::middleware(['auth'])->group(function () {

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    /**
     * 3. STATUS-PROTECTED ROUTES (Agent Only)
     * Scope: Explicitly uses 'id' for internal management routes.
     * Middleware: Prevents editing of Resolved (2) or Cancelled (3) tickets.
     */
    Route::middleware([CheckTicketStatus::class])->group(function () {
        Route::get('/tickets/{ticket:id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket:id}', [TicketController::class, 'update'])->name('tickets.update');
    });
});