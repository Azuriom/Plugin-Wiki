<?php

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

Route::middleware('can:wiki.admin')->group(function () {
    Route::post('pages/position', 'PageController@updateOrder')->name('pages.update-order');
    Route::resource('pages', 'PageController')->except('show');
    Route::resource('categories', 'CategoryController')->except(['index', 'show']);

    Route::resource('pages.attachments', 'PageAttachmentController')->only('store');
    Route::post('pages/attachments/{pendingId}', 'PageAttachmentController@pending')->name('pages.attachments.pending');
});
