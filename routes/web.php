<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AbsensiController;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::resource('kelas', KelasController::class)->except(['show']);
Route::resource('siswa', SiswaController::class)->except(['show']);
Route::get('absensi', [AbsensiController::class, 'create'])->name('absensi.create');
Route::post('absensi/tampil', [AbsensiController::class, 'tampilAbsensi'])->name('absensi.tampil');
Route::post('absensi', [AbsensiController::class, 'store'])->name('absensi.store');

Route::get('absensi/daftar', [AbsensiController::class, 'index'])->name('absensi.index');
Route::get('absensi/detail/{tanggal}/{kelas_id}', [AbsensiController::class, 'showAbsensi'])->name('absensi.show');
