<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DropdownConfigController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

// =============================================
// GUEST Routes (belum login)
// =============================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:login')
        ->name('login.post');
});

// =============================================
// AUTHENTICATED Routes
// =============================================
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Section pages — semua role bisa akses (view)
    Route::get('/master',    [WebsiteController::class, 'index'])->defaults('section', 'master')->name('master');
    Route::get('/domain',    [WebsiteController::class, 'index'])->defaults('section', 'domain')->name('domain');
    Route::get('/hosting',   [WebsiteController::class, 'index'])->defaults('section', 'hosting')->name('hosting');
    Route::get('/akses',     [WebsiteController::class, 'index'])->defaults('section', 'akses')->name('akses');
    Route::get('/finansial', [WebsiteController::class, 'index'])->defaults('section', 'finansial')->name('finansial');
    Route::get('/finansial/export', [WebsiteController::class, 'exportFinansial'])->name('finansial.export');
    Route::get('/reminder',  [WebsiteController::class, 'index'])->defaults('section', 'reminder')->name('reminder');

    // Show detail — semua role bisa akses
    Route::get('/websites/{website}', [WebsiteController::class, 'show'])->name('websites.show');

    // CRUD — hanya superAdmin dan admin
    Route::middleware('role:superAdmin,admin')->group(function () {
        Route::post('/websites',              [WebsiteController::class, 'store'])->name('websites.store');
        Route::put('/websites/{website}',     [WebsiteController::class, 'update'])->name('websites.update');
        Route::delete('/websites/{website}',  [WebsiteController::class, 'destroy'])->name('websites.destroy');
        Route::patch('/websites/{website}/clear', [WebsiteController::class, 'clear'])->name('websites.clear');

        Route::post('/dropdown/add',    [DropdownConfigController::class, 'addOption'])->name('dropdown.add');
        Route::post('/dropdown/remove', [DropdownConfigController::class, 'removeOption'])->name('dropdown.remove');
    });

    // Akun — hanya superAdmin
    Route::middleware('role:superAdmin')->group(function () {
        Route::get('/akun',           [AccountController::class, 'index'])->name('akun');
        Route::post('/akun',          [AccountController::class, 'store'])->name('akun.store');
        Route::put('/akun/{user}',    [AccountController::class, 'update'])->name('akun.update');
        Route::delete('/akun/{user}', [AccountController::class, 'destroy'])->name('akun.destroy');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});