<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\FactionController;

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

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt.auth')->group(function () {
    Route::get('/characters/{username}', [CharacterController::class, 'getByUsername']);
});

Route::get('/saved-music', [MusicController::class, 'getAllSavedMusic']);
Route::prefix('factions')->group(function () {
    Route::get('/', [FactionController::class, 'index']); // Get all factions
    Route::get('/{factionID}/items', [FactionController::class, 'getItems']); // Get items for a faction
});

Route::get('/profile', function (Request $request) {
    return response()->json([
        'user' => $request->auth
    ]);
})->middleware('jwt');