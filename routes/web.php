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

// 1. PUBLIC ROUTES
Route::get('/', function () { 
    return view('welcome'); 
});

// Authentication
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');

// Public Ticket Management
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

// Public Comments (for customers to reply)
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');


// 2. PROTECTED AGENT ROUTES
Route::middleware(['auth'])->group(function () {
    
    // Agent Dashboard
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    
    // Logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Comment Management (Agents only)
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    /**
     * Editing Logic
     * If you get a 404 here:
     * 1. Ensure you are logged in as agent@test.com.
     * 2. Ensure Ticket ID 3 actually exists in phpMyAdmin.
     * 3. Check if CheckTicketStatus middleware is redirecting/failing.
     */
    Route::middleware([CheckTicketStatus::class])->group(function () {
        Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    });
});