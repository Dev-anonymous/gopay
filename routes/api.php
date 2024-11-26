<?php

use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\ConfigAPIController;
use App\Http\Controllers\api\DashAPIController;
use App\Http\Controllers\api\MarchandController;
use App\Http\Controllers\api\PayementController;
use App\Http\Controllers\api\PayoutController;
use App\Http\Controllers\api\RecoveryController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;

#==========   USER AUTH  =======#
// Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/ping', function () {
        return now('Africa/Lubumbashi');
    })->name('ping');

    Route::resource('dash', DashAPIController::class)->only(['index']);

    ########### SOLDE & TRANSFERT
    Route::prefix('marchant')->group(function () {
        Route::get('/solde/{devise?}', [MarchandController::class, 'solde'])->name('marchand.api.solde');
        Route::get('/numero-compte', [MarchandController::class, 'numero_compte'])->name('marchand.api.num_compte');

        Route::get('/transactions', [MarchandController::class, 'transaction'])->name('marchand.api.trans');
        Route::get('/transactions-recentes', [MarchandController::class, 'transaction_recentes'])->name('marchand.api.trans_recent');
        Route::post('/demande-transfert', [MarchandController::class, 'demande_tranfert'])->name('marchand.api.demande_trans');
        Route::get('/demande-transfert', [MarchandController::class, 'get_demande_tranfert']);
        Route::post('/pay/init', [MarchandController::class, 'pay_init'])->name('marchand.api.marchand_pay_init');
        Route::post('/pay/check', [MarchandController::class, 'pay_check'])->name('marchand.api.marchand_pay_check');
        Route::get('/pay-link', [MarchandController::class, 'getpay_link'])->name('marchand.api.pay_link');
        Route::post('/pay-link', [MarchandController::class, 'pay_link']);
        Route::delete('/pay-link/{id}', [MarchandController::class, 'pay_link_del']);
        Route::post('/pin-check', [MarchandController::class, 'pin_check']);
        Route::post('/revoque-payout', [MarchandController::class, 'revoquepayout'])->name('marchand.api.revoquepayout');
        Route::post('/apikey-status', [MarchandController::class, 'apikey_status'])->name('marchand.api.apikeys');
        Route::get('/users', [MarchandController::class, 'users'])->name('marchand.api.users');
        Route::post('/users', [MarchandController::class, 'saveuser']);
        Route::put('/users/{user}', [MarchandController::class, 'updateuser']);
        Route::delete('/users/{user}', [MarchandController::class, 'deleteuser']);

        Route::post('/exchange/willbe', [MarchandController::class, 'willbe'])->name('marchand.api.willbe');
        Route::post('/exchange/exchange', [MarchandController::class, 'exchange'])->name('marchand.api.exchange');

        Route::resource('config', ConfigAPIController::class)->only(['store']);
    });

    #==========   User & Key =======#
    Route::post('/user/update', [UserController::class, 'update'])->name('marchand.api.update_compte');
    Route::post('/user/pass', [UserController::class, 'update_pass'])->name('marchand.api.update_passe');
    Route::get('/user/me', [UserController::class, 'me']); //profil

    Route::get('/user/keys', [UserController::class, 'keys']); // api keys

    ################### ADMIN ROUTES #################
    Route::middleware('admin.mdwr')->prefix('admin')->group(function () {
        Route::get('/feedback', [AdminController::class, 'feedback'])->name('admin.api.feedback');
        Route::get('/marchand', [AdminController::class, 'marchand'])->name('admin.api.marchand');
        Route::post('/marchand', [AdminController::class, 'marchand_add']);
        Route::get('/transaction', [AdminController::class, 'transaction'])->name('admin.api.trans');
        Route::get('/envoi-fonds', [AdminController::class, 'envoi_fonds'])->name('admin.api.cashout');
        Route::post('/envoi-fonds', [AdminController::class, 'maj_envoi_fonds']);
        Route::post('/apikey-status', [AdminController::class, 'apikey_status'])->name('admin.api.apikeys');
    });
});

########### MERCHAND PAYOUT API
Route::middleware('payout.mdwr')->group(function () {
    Route::prefix('payout')->group(function () {
        Route::prefix('v1')->group(function () {
            Route::get('balance', [PayoutController::class, 'balance'])->name('payout.balanceV1');
            Route::get('transfert', [PayoutController::class, 'gettransfert'])->name('payout.transfertV1');
            Route::post('transfert', [PayoutController::class, 'newtransfert']);
            Route::get('transfert-status/{trans_id}', [PayoutController::class, 'statustransfert'])->name('payout.statustransfertV1');
            Route::delete('transfert/{trans_id}', [PayoutController::class, 'deltransfert'])->name('payout.deltransfertV1');
        });
    });
});

########### DEVISE & OPERATEUR
Route::get('/devise', [PayementController::class, 'devise']);
Route::get('/operateur', [PayementController::class, 'operateur']);

#==========   Mot de passe oublié   =======#
Route::post('/user/recovery', [RecoveryController::class, 'recovery']);
Route::post('/user/recovery/check', [RecoveryController::class, 'check']);

Route::post('/feedback', [UserController::class, 'feedback'])->name('feedback');

########## MARCHAND PAIEMENT #########
Route::middleware('paymentProd.mdwr')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('/payment/init', [PayementController::class, 'payinit'])->name('pay.init');
        Route::get('/payment/check/{ref?}', [PayementController::class, 'paycheck'])->name('pay.check');
    });

    Route::prefix('v2')->group(function () {
        Route::post('/payment/init', [PayementController::class, 'payinitV2'])->name('pay.initV2');
        Route::get('/payment/check/{myref?}', [PayementController::class, 'paycheckV2'])->name('pay.checkV2');
    });
});

Route::post('/web/payment/init', [PayementController::class, 'web_pay_init'])->name('web.pay.init');
Route::get('/web/payment/check', [PayementController::class, 'web_pay_check'])->name('web.pay.check');
