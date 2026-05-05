<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CommentController;
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
 * Public Ticket View & Interaction
 * Scope: Uses 'ref' for security.
 * Comments: Allowed for guests so customers can reply to their own tickets.
 */
Route::get('/tickets/{ticket:ref}', [TicketController::class, 'show'])->name('tickets.show');
Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');

// 2. PROTECTED AGENT ROUTES
Route::middleware(['auth'])->group(function () {

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // Comment Management (Agents editing/deleting their own replies)
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    /**
     * 3. STATUS-PROTECTED ROUTES (Agent Only)
     * Middleware: Prevents editing of Resolved (2) or Cancelled (3) tickets.
     */
    Route::middleware([CheckTicketStatus::class])->group(function () {
        Route::get('/tickets/{ticket:id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket:id}', [TicketController::class, 'update'])->name('tickets.update');
    });
});