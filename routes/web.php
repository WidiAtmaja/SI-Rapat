<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/manajemen-pengguna', function () {
    return view('pages.admin.manajemen-pengguna');
});
Route::get('/tabel-manajemen-absensi', function () {
    return view('pages.admin.tabel-manajemen-absensi');
});

Route::get('/absensi', function () {
    return view('pages.absensi');
});

Route::get('/notulensi', function () {
    return view('pages.notulensi');
});

Route::get('/jadwal', function () {
    return view('pages.jadwal');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
