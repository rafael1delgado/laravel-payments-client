<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// clients
Route::get('/clients', ClientController::class)->name("client.index");

// payments
Route::post('/payments', [PaymentController::class, 'store'])->name("payments.store");
Route::get('/payments/{id}', [PaymentController::class, 'show'])->name("payments.index");
