<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\CheckTicketStatus;

// 1. PUBLIC ROUTES (Essential for welcome.blade.php)
Route::get('/', function () {
    return view('welcome');
});

// These routes fix the "Route not defined" error
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/search', [TicketController::class, 'search'])->name('tickets.search');
Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');

// Authentication
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

// 2. PROTECTED AGENT ROUTES
Route::middleware(['auth'])->group(function () {

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // 3. STATUS-PROTECTED ROUTES
    Route::middleware([CheckTicketStatus::class])->group(function () {
        Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    });
});