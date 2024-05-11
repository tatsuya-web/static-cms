<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('app.site_tree.index');
});

require __DIR__.'/auth.php';
