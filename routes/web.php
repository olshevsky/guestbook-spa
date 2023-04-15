<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GuestbookController;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::get('guestbook', [GuestbookController::class, 'index'])->name('guestbook');
Route::get('guestbook/create', [GuestbookController::class, 'create'])->name('guestbook.create');
Route::post('guestbook', [GuestbookController::class, 'store'])->name('guestbook.store');
Route::get('guestbook/{message}/edit', [GuestbookController::class, 'edit'])->name('guestbook.edit');
Route::put('guestbook/{message}', [GuestbookController::class, 'update'])->name('guestbook.update');
Route::delete('guestbook/{message}', [GuestbookController::class, 'destroy'])->name('guestbook.destroy');
