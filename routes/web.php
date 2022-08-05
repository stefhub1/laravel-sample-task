<?php

use App\Http\Controllers\CardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\IndexController;
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

Route::get('/', [IndexController::class, 'index']);

// Columns
Route::resource('columns', ColumnController::class)
	->except(['create', 'edit', 'show']);

// Cards
Route::resource('cards', CardController::class)
	->except(['index', 'create', 'edit', 'show', 'destroy']);

Route::get('/export-db', [IndexController::class, 'exportDatabase']);
