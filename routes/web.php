<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRole;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', CheckRole::class . ':admin,owner'])->group(function () {
    Route::get('/users/jabatan', [UserController::class, 'jabatan'])->name('users.jabatan');
    Route::get('/users/data-jabatan', [UserController::class, 'dataJabatan'])->name('users.data-jabatan');
    Route::post('/users/store-jabatan', [UserController::class, 'storeJabatan'])->name('users.store-jabatan');
    Route::post('/users/jabatan/{id}', [UserController::class, 'updateJabatan']);
    Route::get('/users/karyawan', [UserController::class, 'karyawan'])->name('users.karyawan');
    Route::get('/users/data-karyawan', [UserController::class, 'dataKaryawan'])->name('users.data-karyawan');
    Route::post('/users/store-karyawan', [UserController::class, 'storeKaryawan'])->name('users.store-karyawan');
    Route::post('/users/karyawan/update/{id}', [UserController::class, 'updateKaryawan']);
    Route::get('/users/karyawan/edit/{id}', [UserController::class, 'editKaryawan']);
    Route::delete('/users/karyawan/delete/{id}', [UserController::class, 'deleteKaryawan']);

    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi');
    Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/data', [AbsensiController::class, 'data'])->name('absensi.data');

    Route::delete('/users/jabatan/{id}', [UserController::class, 'deleteJabatan']);
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
