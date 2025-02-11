<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\RolePermissionController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('password-reset', [AuthController::class, 'passwordReset']); // OTP or email-based
Route::post('/password/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/password/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);


// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('wallet/create', [WalletController::class, 'createWallet']);
//     Route::post('wallet/recharge', [WalletController::class, 'rechargeWallet']);
//     Route::post('wallet/transfer', [WalletController::class, 'transferFunds']);
//     Route::get('wallet/transactions', [WalletController::class, 'transactionHistory']);
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::post('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::post('/wallet/create', [WalletController::class, 'createWallet']);
    Route::get('/wallet/balance', [WalletController::class, 'balance']);
    Route::post('/wallet/credit', [WalletController::class, 'credit']);
    Route::post('/wallet/debit', [WalletController::class, 'debit']);
    Route::get('/wallet/transactions', [WalletController::class, 'transactionHistory']);
    Route::post('/wallet/transfer', [WalletController::class, 'transfer']);
    Route::post('/wallet/recharge', [WalletController::class, 'recharge']);
    Route::post('/wallet/commission', [WalletController::class, 'deductCommission']);
});


Route::post('/roles', [RolePermissionController::class, 'createRole']);
Route::post('/roles/assign', [RolePermissionController::class, 'assignRoleToUser']);
Route::post('/permissions', [RolePermissionController::class, 'createPermission']);
Route::post('/permissions/assign', [RolePermissionController::class, 'assignPermissionToRole']);

Route::middleware('role:admin')->get('/admin-only', function () {
    return response()->json(['message' => 'Admin Access']);
});

Route::middleware('role:partner')->get('/partner-only', function () {
    return response()->json(['message' => 'Partner Access']);
});
