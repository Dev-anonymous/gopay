<?php

use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\PayementController;
use App\Http\Controllers\api\RecoveryController;
use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Route;

#==========   USER AUTH  =======#
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    ########### SOLDE & TRANSFERT
    Route::get('/solde/{devise?}', [PayementController::class, 'solde']); //liste solde user
    Route::get('/numero-compte', [PayementController::class, 'numero_compte']); //affiche le numero de compte du user

    Route::get('/transaction', [PayementController::class, 'transaction']); //liste transaction
    Route::post('/demande-transfert', [PayementController::class, 'demande_tranfert']); //demande tranfert de fonds
    Route::get('/demande-transfert', [PayementController::class, 'get_demande_tranfert']);
    // Route::post('/transfert', [PayementController::class, 'transfert']); //transfert argent vers un compte

    #==========   User & Key =======#
    Route::post('/user/update', [UserController::class, 'update']); //update
    Route::post('/user/pass', [UserController::class, 'update_pass']); //update password
    Route::get('/user/me', [UserController::class, 'me']); //profil

    Route::get('/user/keys', [UserController::class, 'keys']); // api keys

    ################### ADMIN ROUTES #################
    Route::middleware('admin.mdwr')->group(function () {
        Route::get('/admin/feedback', [AdminController::class, 'feedback']);
        Route::get('/admin/marchand', [AdminController::class, 'marchand']);
        Route::post('/admin/marchand', [AdminController::class, 'marchand_add']);
        Route::get('/admin/transaction', [AdminController::class, 'transaction']);
        Route::get('/admin/envoi-fonds', [AdminController::class, 'envoi_fonds']);
        Route::post('/admin/envoi-fonds', [AdminController::class, 'maj_envoi_fonds']);
        Route::post('/admin/apikey-status', [AdminController::class, 'apikey_status']);
    });
});

########### DEVISE & OPERATEUR
Route::get('/devise', [PayementController::class, 'devise']);
Route::get('/operateur', [PayementController::class, 'operateur']);

#==========   Mot de passe oublié   =======#
Route::post('/user/recovery', [RecoveryController::class, 'recovery']);
Route::post('/user/recovery/check', [RecoveryController::class, 'check']);

Route::post('/feedback', [UserController::class, 'feedback']);

########## MARCHAND PAIEMENT #########
Route::middleware('paymentProd.mdwr')->group(function () {
    Route::post('/payment/init', [PayementController::class, 'payinit']); //initiaiser un paiement
    Route::post('/payment/check/{ref?}', [PayementController::class, 'paycheck']); //verifier le paiement
});
