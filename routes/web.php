<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\NotulenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RapatController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/manajemen-pengguna', function () {
    return view('pages.admin.manajemen-pengguna');
});
Route::get('/tabel-manajemen-absensi', function () {
    return view('pages.admin.tabel-manajemen-absensi');
});

Route::get('/notulensi-detail', function () {
    return view('pages.partials.detail-notulensi');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('rapat', RapatController::class);
});

Route::middleware(['auth'])->group(function () {

    // Route untuk semua user (admin & pegawai)
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');

    // Route khusus Admin
    Route::middleware(['admin'])->group(function () {
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/absensi/{rapatId}', [AbsensiController::class, 'show'])->name('absensi.show');
        Route::delete('/absensi/rapat/{rapatId}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
        Route::post('/notulensi/store', [NotulenController::class, 'store'])->name('notulensi.store');
        Route::delete('/notulensi/{id}', [NotulenController::class, 'destroy'])->name('notulensi.destroy');
        Route::put('/notulensi/{id}', [NotulenController::class, 'update'])->name('notulensi.update');
    });

    // Route untuk Pegawai (update kehadiran mereka sendiri)
    Route::put('/absensi/{absensi}', [AbsensiController::class, 'update'])->name('absensi.update');

    Route::get('/notulensi', [NotulenController::class, 'index'])->name('notulensi.index');
    Route::get('/notulensi/{rapatId}', [NotulenController::class, 'show'])->name('notulensi.show');
    Route::get('/rapat/{id}', [RapatController::class, 'show'])->name('rapat.show');
    Route::get('/notulensi/download/{id}', [NotulenController::class, 'download'])->name('notulensi.download');
    Route::resource('notulensi', App\Http\Controllers\NotulenController::class);
});

require __DIR__ . '/auth.php';
