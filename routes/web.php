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

// Authentication logic
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('authenticate');

// Public Ticket Management (Search, Create, and View)
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

/**
 * Comment Management - Public POST
 * This is placed OUTSIDE the auth middleware so that guests (customers) 
 * can reply to their own tickets using the 'comments.store' route.
 */
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');


// 2. PROTECTED AGENT ROUTES (Requires Login)
Route::middleware(['auth'])->group(function () {
    
    // Agent Dashboard
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    
    // Logout Logic
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    /**
     * Comment Management - Protected Actions
     * Only logged-in agents can update or delete replies.
     */
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Editing Logic (Middleware for Status check: prevents editing closed tickets)
    Route::middleware([CheckTicketStatus::class])->group(function () {
        Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    });
});