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

// Views
Route::get('/', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard.view');
Route::get('/app/{app}', [App\Http\Controllers\DashboardController::class, 'app'])->name('app.view');
Route::get('/result/{id}', [App\Http\Controllers\DashboardController::class, 'show'])->name('show.view');

// Api
Route::get('/api/results', [App\Http\Controllers\ApiController::class, 'results'])->name('results');
Route::post('/api/results/delete', [App\Http\Controllers\ApiController::class, 'delete'])->name('results.delete');
Route::get('/api/applications', [App\Http\Controllers\ApiController::class, 'listApps'])->name('app.list');
Route::get('/api/results/{id}', [App\Http\Controllers\ApiController::class, 'result'])->name('result');
Route::get('/api/app/{app}', [App\Http\Controllers\ApiController::class, 'application'])->name('application');

Route::get('/api/stats/{app}', [App\Http\Controllers\StatsController::class, 'countAppStats'])->name('stats');
Route::get('/api/stats', [App\Http\Controllers\StatsController::class, 'countOverallStats'])->name('stats.holistic');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

