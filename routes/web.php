<?php

use App\Http\Controllers\AdminWebController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PayementController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\MarchandWebController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/payment-callback/{cb_code?}', [PayementController::class, 'payCallBack'])->name('payment.callback.web');

Route::get('', [AppController::class, 'index'])->name('app.index');
Route::get('login', [AppController::class, 'login'])->name('app.login');

Route::post('/auth/login', [AuthController::class, 'login'])->name('login.web');
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::any('/auth/logout', [AuthController::class, 'logout'])->name('logout.web');

    Route::middleware('admin.mdwr')->group(function () {
        Route::prefix('admin-dash')->group(function () {
            Route::get('', [AdminWebController::class, 'index'])->name('admin.web.index');
            Route::get('transaction', [AdminWebController::class, 'transaction'])->name('admin.web.trans');
            Route::get('cash-out', [AdminWebController::class, 'cash_out'])->name('admin.web.cashout');
            Route::get('merchant', [AdminWebController::class, 'merchant'])->name('admin.web.merchent');
            Route::get('feedback', [AdminWebController::class, 'feedback'])->name('admin.web.feedback');
        });
    });

    Route::middleware('marchand.mdwr')->group(function () {
        Route::prefix('merchant-dash')->group(function () {
            Route::get('', [MarchandWebController::class, 'index'])->name('marchand.web.index');
            Route::get('transaction', [MarchandWebController::class, 'transaction'])->name('marchand.web.trans');
            Route::get('cash-out', [MarchandWebController::class, 'cash_out'])->name('marchand.web.cashout');
            Route::get('integration', [MarchandWebController::class, 'integration'])->name('marchand.web.integration');
            Route::get('account', [MarchandWebController::class, 'compte'])->name('marchand.web.compte');
            Route::get('pay-link', [MarchandWebController::class, 'lien_pay'])->name('marchand.web.lien_pay');
        });
    });
});
