<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RequesterController;

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('requesters')->name('requesters.')->group(function () {
        Route::get('/', [RequesterController::class, 'index'])->name('index');
        Route::get('/create', [RequesterController::class, 'create'])->name('create');
        Route::post('/', [RequesterController::class, 'store'])->name('store');
        Route::get('/{requester}', [RequesterController::class, 'show'])->name('show');
    });

    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', [App\Http\Controllers\TicketController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\TicketController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\TicketController::class, 'store'])->name('store');
        Route::get('/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('show');
        Route::patch('/{ticket}/status', [App\Http\Controllers\TicketController::class, 'updateStatus'])->name('update-status');
        
        Route::post('/{ticket}/replies', [App\Http\Controllers\TicketReplyController::class, 'store'])->name('replies.store');
    });

    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
    });
});

require __DIR__.'/auth.php';
