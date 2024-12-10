<?php

use App\Http\Controllers\ResourceController;
use App\Http\Controllers\JWTAuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/*
|--------------------------------------------------------------------------
| Authenticacion
|--------------------------------------------------------------------------
*/

Route::post('register', [JWTAuthController::class, 'register']);
Route::post('login', [JWTAuthController::class, 'login']);



/*
|--------------------------------------------------------------------------
| Api RestFull
|--------------------------------------------------------------------------
*/

Route::middleware([JwtMiddleware::class])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Resources
    |--------------------------------------------------------------------------
    */
    Route::get('/resources', [ResourceController::class, 'list']);
    Route::get('/resources/{id}/availability', [ResourceController::class, 'availability']);


    /*
    |--------------------------------------------------------------------------
    | Reservation
    |--------------------------------------------------------------------------
    */
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'cancelledReservation']);
});
