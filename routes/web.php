<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController; // PASTIKAN INI DITAMBAHKAN
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rute Profile (Existing)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Baru: Manajemen Kelas dan Siswa
    // Menggunakan Route::controller() untuk mempersingkat penulisan
    Route::controller(SiswaController::class)->group(function () {
        Route::get('/siswa', 'index')->name('siswa.index');
        Route::get('/siswa/create', 'create')->name('siswa.create');
        Route::post('/siswa', 'store')->name('siswa.store');
        // Rute EDIT dan UPDATE BARU
        Route::get('/siswa/{siswa}/edit', 'edit')->name('siswa.edit');
        Route::put('/siswa/{siswa}', 'update')->name('siswa.update');
        // Rute untuk Hapus (jika diperlukan di masa depan)
        Route::delete('/siswa/{siswa}', 'destroy')->name('siswa.destroy');
    });
});

require __DIR__ . '/auth.php';
