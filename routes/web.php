<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login/magic/{token}', [App\Http\Controllers\Auth\MagicLinkController::class, 'verify'])->name('magic.verify');
