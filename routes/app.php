<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PreviewController;
use App\Http\Controllers\SiteTreeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\MediaController;

Route::name('app.')->prefix('_app')->group(function () {
    // site_tree
    Route::name('site_tree.')->prefix('site_tree')->group(function () {
        Route::get('/', [SiteTreeController::class, 'index'])
            ->name('index');
        Route::get('/create/{tree?}', [SiteTreeController::class, 'create'])
            ->name('create');
        Route::post('/store/{tree?}', [SiteTreeController::class, 'store'])
            ->name('store');
        Route::get('/edit/{tree}', [SiteTreeController::class, 'edit'])
            ->name('edit');
        Route::post('/update/{tree}', [SiteTreeController::class, 'update'])
            ->name('update');
        Route::delete('/destroy', [SiteTreeController::class, 'destroy'])
            ->name('destroy');
    });

    // user
    Route::name('user.')->prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])
            ->name('index');
        Route::get('/create', [UserController::class, 'create'])
            ->name('create');
        Route::post('/store', [UserController::class, 'store'])
            ->name('store');
        Route::get('/edit/{user}', [UserController::class, 'edit'])
            ->name('edit');
        Route::post('/update/{user}', [UserController::class, 'update'])
            ->name('update');
        Route::delete('/destroy', [UserController::class, 'destroy'])
            ->name('destroy');
    });

    // log
    Route::name('log.')->prefix('log')->group(function () {
        Route::get('/', LogController::class)
            ->name('index');
    });

    // media
    Route::name('media.')->prefix('media')->group(function () {
        Route::get('/download/{media}', [MediaController::class, 'download'])
            ->name('download');
    });
});

// preview
// /も含めて全てのパスを受け付ける
Route::get('/{any?}', PreviewController::class)
    ->where('any', '.*')
    ->name('preview');