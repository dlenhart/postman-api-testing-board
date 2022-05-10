<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.view');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/app/{app}', [App\Http\Controllers\DashboardController::class, 'app'])->name('app.view');
Route::get('/result/{id}', [App\Http\Controllers\DashboardController::class, 'show'])->name('show.view');
Route::get('/api/results', [App\Http\Controllers\Dashboard\DashboardApiController::class, 'results'])->name('results');
Route::get('/api/applications', [App\Http\Controllers\Dashboard\DashboardApiController::class, 'listApps'])->name('app.list');
Route::post('/api/results/delete', [App\Http\Controllers\Dashboard\DashboardApiController::class, 'delete'])->name('results.delete');
Route::get('/api/results/{id}', [App\Http\Controllers\Dashboard\DashboardApiController::class, 'result'])->name('result');
Route::get('/api/app/{app}', [App\Http\Controllers\Dashboard\DashboardApiController::class, 'application'])->name('application');
Route::get('/api/stats/{app}', [App\Http\Controllers\Api\StatsController::class, 'countAppStats'])->name('stats');
Route::get('/api/stats', [App\Http\Controllers\Api\StatsController::class, 'countOverallStats'])->name('stats.holistic');

Auth::routes();

