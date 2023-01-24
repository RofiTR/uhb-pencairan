<?php

use App\Http\Controllers\Api\Configurations\Approver\Index as Approver;
use App\Http\Controllers\Api\Users\Dapo\Index as UserDapo;
use App\Http\Controllers\Api\Users\Model\Index as UserModel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SsoController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\System\ConfigurationController;
use App\Http\Controllers\System\RoleController;
use App\Http\Controllers\System\UserController;
use App\Http\Controllers\System\ToolsController;
use App\Http\Controllers\System\PermissionController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\WithdrawalController;

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

Route::controller(SsoController::class)->name('sso.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/login', 'index')->name('login');
        Route::get('oauth/authorize/{id}', 'store')->name('store');
    });
    Route::middleware('auth')->group(function () {
        Route::post('/logout', 'destroy')->name('destroy');
    });
});

Route::middleware(['auth', 'menu'])->group(function () {

    Route::post('api/configurations/approver', Approver::class)->name('api.configurations.approver');
    Route::post('api/users/dapo', UserDapo::class)->name('api.users.dapo');
    Route::post('api/users/model', UserModel::class)->name('api.users.model');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('pengajuan/detail/{proposal}', [ProposalController::class, 'show'])->middleware(['permission:proposal view'])->name('proposal.show');
    Route::get('pengajuan/{category}', [ProposalController::class, 'create'])->middleware(['permission:proposal add'])->name('proposal.create');
    Route::get('verifikasi', [VerificationController::class, 'index'])->middleware(['role:Keuangan|Pimpinan'])->name('verification.index');
    Route::get('lpj', [VerificationController::class, 'index'])->middleware(['permission:proposal report index'])->name('lpj.index');
    Route::get('verifikasi/{type}/{proposal}', [VerificationController::class, 'show'])->middleware(['role:Keuangan|Pimpinan'])->name('verification.show');
    Route::get('pencairan', [WithdrawalController::class, 'index'])->middleware(['role:Kasir'])->name('withdrawal.index');
    Route::get('pencairan/{proposal}', [WithdrawalController::class, 'show'])->middleware(['role:Kasir'])->name('withdrawal.show');
    Route::get('laporan', [ReportController::class, 'index'])->middleware(['role:Kasir|Yayasan'])->name('report.index');
    Route::get('laporan/{proposal}', [ReportController::class, 'show'])->middleware(['role:Kasir|Yayasan'])->name('report.show');
    Route::get('notifikasi', [DashboardController::class, 'index'])->middleware(['role:Kasir|Yayasan'])->name('notification.index');

    Route::name('system.')->middleware(['role:SA'])->group(function () {
        Route::prefix('role')->name('role.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
        });
        Route::prefix('permission')->name('permission.')->group(function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
        });
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
        });
        Route::prefix('pengaturan')->name('configuration.')->group(function () {
            Route::get('/', [ConfigurationController::class, 'index'])->name('index');
            Route::get('/{user}', [ConfigurationController::class, 'show'])->name('show');
        });
        Route::get('/tools', [ToolsController::class, 'index'])->name('tools')->middleware(['role:SA']);
    });
});
