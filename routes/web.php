<?php

use App\Http\Controllers\Admin\DokterController;
use App\Http\Controllers\Admin\JadwalPeriksaController;
use App\Http\Controllers\Admin\ObatController;
use App\Http\Controllers\Admin\PasienController;
use App\Http\Controllers\Admin\PoliController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1')
    ->name('login.store');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/admin/dashboard', 'admin.dashboard')
        ->middleware('role:admin')
        ->name('admin.dashboard');
    Route::view('/dokter/dashboard', 'dokter.dashboard')
        ->middleware('role:dokter')
        ->name('dokter.dashboard');
    Route::view('/pasien/dashboard', 'pasien.dashboard')
        ->middleware('role:pasien')
        ->name('pasien.dashboard');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::resource('poli', PoliController::class)->except('show');
        Route::resource('dokter', DokterController::class)->except('show');
        Route::resource('pasien', PasienController::class)->except('show');
        Route::resource('obat', ObatController::class)->except('show');
        Route::resource('jadwal-periksa', JadwalPeriksaController::class)->except('show');
    });
});
