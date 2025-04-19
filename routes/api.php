<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\BusBookingController;
use App\Http\Controllers\FlightBookingController;
use App\Http\Controllers\HotelBookingController;


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
    
    Route::prefix('wallet')->group(function () {
        Route::post('/create', [WalletController::class, 'createWallet']);
        Route::get('/balance', [WalletController::class, 'balance']);
        Route::post('/credit', [WalletController::class, 'credit']);
        Route::post('/debit', [WalletController::class, 'debit']);
        Route::get('/transactions', [WalletController::class, 'transactionHistory']);
        Route::post('/transfer', [WalletController::class, 'transfer']);
        Route::post('/recharge', [WalletController::class, 'recharge']);
        Route::post('/commission', [WalletController::class, 'deductCommission']);
    });
    
    Route::prefix('bus')->group(function () {
        Route::post('/search-bus', [BusBookingController::class, 'searchBus']);
        Route::post('/seat-layout', [BusBookingController::class, 'seatLayout']);
        Route::post('/boarding-point', [BusBookingController::class, 'boardingPoint']);
        Route::post('/block-seat', [BusBookingController::class, 'blockSeat']);
        Route::post('/book-bus', [BusBookingController::class, 'bookBus']);
        Route::post('/getbooking-detail', [BusBookingController::class, 'getBookingDetail']);
        Route::post('/cancel-bus', [BusBookingController::class, 'cancelBus']);

    });

    Route::prefix('flight')->group(function () {
        Route::post('/search-flight', [FlightBookingController::class, 'searchFlight']);
        Route::post('/farerule', [FlightBookingController::class, 'fareRule']);
        Route::post('/ssr', [FlightBookingController::class, 'ssr']);
        Route::post('/fare-confirmation', [FlightBookingController::class, 'fareConfirmation']);
        Route::post('/book-flight', [FlightBookingController::class, 'bookFlight']);
        Route::post('/get-booking-detail', [FlightBookingController::class, 'getBookingDetail']);
        Route::post('/cancel-flight', [FlightBookingController::class, 'cancelFlight']);

    });

    Route::prefix('hotel')->group(function () {
        Route::post('/search-hotel', [HotelBookingController::class, 'searchHotel']);
        Route::post('/get-hotel-info', [HotelBookingController::class, 'getHotelInfo']);
        Route::post('/get-room-info', [HotelBookingController::class, 'getRoomInfo']);
        Route::post('/block-room', [HotelBookingController::class, 'blockRoom']);
        Route::post('/book-hotel', [HotelBookingController::class, 'bookHotel']);
        Route::post('/get-booking-detail', [HotelBookingController::class, 'getBookingDetail']);
        Route::post('/cancel-hotel', [HotelBookingController::class, 'cancelHotel']);
    });
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
