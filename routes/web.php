<?php

use Azuriom\Plugin\Wiki\Controllers\CategoryController;
use Azuriom\Plugin\Wiki\Controllers\CategoryPageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your plugin. These
| routes are loaded by the RouteServiceProvider of your plugin within
| a group which contains the "web" middleware group and your plugin name
| as prefix. Now create something great!
|
*/

Route::get('/', [CategoryController::class, 'index'])->name('index');
Route::get('/search', [CategoryController::class, 'search'])->name('search');
Route::get('/{category:slug}', [CategoryController::class, 'show'])->name('show');
Route::get('/{category:slug}/{page:slug}', [CategoryPageController::class, 'show'])
    ->name('pages.show');
