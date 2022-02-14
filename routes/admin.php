<?php

use Azuriom\Plugin\Wiki\Controllers\Admin\CategoryController;
use Azuriom\Plugin\Wiki\Controllers\Admin\PageAttachmentController;
use Azuriom\Plugin\Wiki\Controllers\Admin\PageController;
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
    Route::post('pages/position', [PageController::class, 'updateOrder'])->name('pages.update-order');
    Route::resource('pages', PageController::class)->except('show');
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);

    Route::resource('pages.attachments', PageAttachmentController::class)->only('store');
    Route::post('pages/attachments/{pendingId}', [PageAttachmentController::class, 'pending'])->name('pages.attachments.pending');
});
