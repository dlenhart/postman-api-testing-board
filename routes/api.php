<?php

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

Route::namespace('Api')->prefix('v1')->group(function() {
    Route::post('import', [App\Http\Controllers\ImportController::class, 'import'])->name('import');
    Route::get('/stats/{app}', [App\Http\Controllers\StatsController::class, 'countAppStats'])->name('stats');
    Route::get('/stats', [App\Http\Controllers\StatsController::class, 'countOverallStats'])->name('stats.holistic');
});
