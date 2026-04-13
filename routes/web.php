<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RequesterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin: User Management
    Route::middleware('role:admin')->prefix('users')->name('users.')->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\UserController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('edit');
        Route::patch('/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
    });

    // Admin: Requester Management
    Route::middleware('role:admin')->prefix('requesters')->name('requesters.')->group(function () {
        Route::get('/', [RequesterController::class, 'index'])->name('index');
        Route::get('/create', [RequesterController::class, 'create'])->name('create');
        Route::post('/', [RequesterController::class, 'store'])->name('store');
        Route::get('/{requester}', [RequesterController::class, 'show'])->name('show');
        Route::get('/{requester}/edit', [RequesterController::class, 'edit'])->name('edit');
        Route::patch('/{requester}', [RequesterController::class, 'update'])->name('update');
        Route::delete('/{requester}', [RequesterController::class, 'destroy'])->name('destroy');
    });

    // Tickets (all roles, role logic handled in controller)
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [App\Http\Controllers\TicketController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\TicketController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('show');
        Route::get('/{ticket}/edit', [App\Http\Controllers\TicketController::class, 'edit'])->name('edit');
        Route::patch('/{ticket}', [App\Http\Controllers\TicketController::class, 'update'])->name('update');
        Route::patch('/{ticket}/status', [App\Http\Controllers\TicketController::class, 'updateStatus'])->name('update-status');
        Route::patch('/{ticket}/assign', [App\Http\Controllers\TicketController::class, 'assign'])->name('assign');
        Route::post('/{ticket}/replies', [App\Http\Controllers\TicketReplyController::class, 'store'])->name('replies.store');
    });

    // Admin: Category Management
    Route::middleware('role:admin')->prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::patch('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Admin + Supervisor: Reports
    Route::middleware('role:admin,supervisor')->prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');
    });
});

require __DIR__.'/auth.php';
