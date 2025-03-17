<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use SePay\SePay\Http\Controllers\SePayController;

Route::group([
    'prefix' => 'api/sepay',
    'as' => 'sepay.',
    'middleware' => ['api'],
], function () {
    Route::any('/webhook', [SePayController::class, 'webhook'])->name('webhook');
});
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
