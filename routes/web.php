<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\NotulenController;
use App\Http\Controllers\PerangkatDaerahController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RapatController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check() ? redirect('/rapat') : redirect('/login');
});

Route::get('/rapat', [RapatController::class, 'index'])
    ->middleware(['auth'])
    ->name('rapat.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {

    // Route khusus Admin
    Route::middleware(['admin'])->group(function () {

        //  MANAJEMEN USER
        Route::get('/manajemen-pengguna', [UserController::class, 'index'])->name('user.index');
        Route::post('/manajemen-pengguna', [UserController::class, 'store'])->name('user.store');
        Route::put('/manajemen-pengguna/{user}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/manajemen-pengguna/{user}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::post('/manajemen-pengguna/import-excel', [UserController::class, 'importExcel'])->name('user.import-excel');
        Route::get('/manajemen-pengguna/export-user', [UserController::class, 'exportExcel'])->name('user.export');
        Route::get('/manajemen-pengguna/download-template', [UserController::class, 'downloadTemplate'])->name('user.download-template');

        //  Perangkat Daerah
        Route::get('/perangkat-daerah', [PerangkatDaerahController::class, 'index'])->name('perangkat-daerah.index');
        Route::post('/perangkat-daerah', [PerangkatDaerahController::class, 'store'])->name('perangkat-daerah.store');
        Route::put('/perangkat-daerah/{perangkat_daerah}', [PerangkatDaerahController::class, 'update'])->name('perangkat-daerah.update');
        Route::delete('/perangkat-daerah/{perangkat_daerah}', [PerangkatDaerahController::class, 'destroy'])->name('perangkat-daerah.destroy');

        // Rapat
        Route::post('/rapat', [RapatController::class, 'store'])->name('rapat.store');
        Route::put('/rapat/{rapat}', [RapatController::class, 'update'])->name('rapat.update');
        Route::delete('/rapat/{rapat}', [RapatController::class, 'destroy'])->name('rapat.destroy');

        // Absensi
        Route::post('/absensi', [AbsensiController::class, 'store'])->name('absensi.store');
        Route::get('/absensi/{rapat}', [AbsensiController::class, 'show'])->name('absensi.show');
        Route::delete('/absensi/{rapat}', [AbsensiController::class, 'destroy'])->name('absensi.destroy');
        Route::get('/absensi/rapat/{rapat}/cetak-pdf', [AbsensiController::class, 'cetakAbsensiPDF'])->name('absensi.cetak-pdf');

        // Notulensi
        Route::post('/notulensi/store', [NotulenController::class, 'store'])->name('notulensi.store');
        Route::put('/notulensi/{notulen}', [NotulenController::class, 'update'])->name('notulensi.update');
        Route::delete('/notulensi/{notulen}', [NotulenController::class, 'destroy'])->name('notulensi.destroy');
    });

    // Rapat (Akses Umum)
    Route::get('/rapat', [RapatController::class, 'index'])->name('rapat.index');
    Route::get('/rapat/{rapat}', [RapatController::class, 'show'])->name('rapat.show'); // <-- {rapat}
    Route::get('/rapat/download-materi/{rapat}', [RapatController::class, 'downloadRapat'])->name('materi.download');
    Route::get('/rapat/download-surat/{rapat}', [RapatController::class, 'downloadSurat'])->name('surat.download');

    // Search
    Route::get('/search', [SearchController::class, 'search'])->name('global.search');

    // Absensi (Akses Umum)
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::put('/absensi/{absensi}', [AbsensiController::class, 'update'])->name('absensi.update'); // <-- {absensi} (ini sudah benar)

    // Notulensi (Akses Umum)
    Route::get('/notulensi', [NotulenController::class, 'index'])->name('notulensi.index');
    Route::get('/notulensi/{rapat}', [NotulenController::class, 'show'])->name('notulensi.show'); // <-- {rapat}
    Route::get('/notulensi/download/{notulen}', [NotulenController::class, 'download'])->name('notulensi.download'); // <-- {notulen}
});

require __DIR__ . '/auth.php';
