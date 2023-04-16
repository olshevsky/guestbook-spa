<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GuestbookController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('/', [GuestbookController::class, 'index'])->name('guestbook');
Route::get('guestbook', [GuestbookController::class, 'index'])->name('guestbook');
Route::get('guestbook/create', [GuestbookController::class, 'create'])->name('guestbook.create');
Route::post('guestbook', [GuestbookController::class, 'store'])->name('guestbook.store');
Route::get('guestbook/{message}/edit', [GuestbookController::class, 'edit'])->name('guestbook.edit');
Route::put('guestbook/{message}', [GuestbookController::class, 'update'])->name('guestbook.update');
Route::delete('guestbook/{message}', [GuestbookController::class, 'destroy'])->name('guestbook.destroy');


Route::get('user', [UserController::class, 'index'])->name('user');
Route::get('user/create', [UserController::class, 'create'])->name('user.create');
Route::post('user', [UserController::class, 'store'])->name('user.store');
Route::get('user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
Route::put('user/{user}', [UserController::class, 'update'])->name('user.update');
Route::delete('user/{user}', [UserController::class, 'destroy'])->name('user.destroy');
