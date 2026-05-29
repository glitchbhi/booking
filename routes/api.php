<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GroundSlotsController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Ground Slots API Routes
Route::get('/grounds/{ground}/slots', [GroundSlotsController::class, 'getSlots']);
Route::get('/grounds/{ground}/slots-range', [GroundSlotsController::class, 'getSlotsDateRange']);
Route::get('/grounds/{ground}/schedule-info', [GroundSlotsController::class, 'getScheduleInfo']);
Route::post('/grounds/{ground}/regenerate-slots', [GroundSlotsController::class, 'regenerateSlots'])->middleware('auth');
