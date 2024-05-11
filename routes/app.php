<?php

use Illuminate\Support\Facades\Route;

Route::name('app.')->prefix('app')->group(function () {
    // site_tree
    Route::name('site_tree.')->prefix('site_tree')->group(function () {
        Route::get('/', [App\Http\Controllers\SiteTreeController::class, 'index'])
            ->name('index');
    });
});