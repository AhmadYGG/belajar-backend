<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\FactionController;
use App\Http\Controllers\OrderController;

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

Route::post('/login', [AuthController::class, 'loginUser']);
Route::get('/music', [MusicController::class, 'listMusic']);
Route::prefix('factions')->group(function () {
    Route::get('/', [FactionController::class, 'index']);
    Route::get('/{factionID}/items', [FactionController::class, 'getItems']);
});

Route::middleware('jwt')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::get('/orders/{id}/receipt', [OrderController::class, 'receipt']);
    Route::get('/characters', [CharacterController::class, 'getByUsername']);
    Route::post('/characters', [CharacterController::class, 'bindCharacter']);
    Route::get('/char-check', [CharacterController::class, 'getAccountCharacters']);
});
