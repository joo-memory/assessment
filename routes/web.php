<?php
require __DIR__ . '/auth.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileDownloadController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Auth\CustomRegisterController;

Route::post('/login', [CustomLoginController::class, 'login']);
Route::get('/register', [CustomRegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [CustomRegisterController::class, 'register']);
Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::view('/', 'dashboard')->name('dashboard');

        // Route for the stores page
        Route::get('/stores', [DashboardController::class, 'stores'])->name('stores');

        // Route for store statistics page
        Route::get('/stores/{id}', [DashboardController::class, 'stats'])->name('stats');

        // Route for the brands page
        Route::get('/brands', [DashboardController::class, 'brands'])->name('brands');

        Route::get('/brands/{id}', [DashboardController::class, 'brand_stats'])->name('brand_stats');
    });
    // Route for exporting data
    Route::get('/download/{filename}', [FileDownloadController::class, 'download'])->name('download.file');
    Route::post('/export', [DashboardController::class, 'export'])->name('export');
    Route::post('/export_brand', [DashboardController::class, 'brand_export'])->name('brand_export');
});
