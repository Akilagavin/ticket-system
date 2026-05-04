<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CommentController;
use App\Http\Middleware\CheckTicketStatus;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. PUBLIC ROUTES (Anyone can access)
Route::get('/', function () { 
    return view('welcome'); 
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');

// Ticket Public Access
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

/**
 * Comment Routes
 * Registered outside of auth middleware so customers can reply to their tickets.
 * This registers: comments.store, comments.update, and comments.destroy.
 */
Route::resource('comments', CommentController::class)->only(['store', 'update', 'destroy']);


// 2. PROTECTED AGENT ROUTES (Gatekeeper applied)
Route::middleware(['auth'])->group(function () {
    
    // The Tickets Dashboard Table
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    
    // Logout Logic
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Editing Logic (Middleware for Status check)
    Route::middleware([CheckTicketStatus::class])->group(function () {
        Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    });
});