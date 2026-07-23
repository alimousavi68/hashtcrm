<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/start', \App\Livewire\StartOnboarding::class)->name('start');

Route::get('/login/magic/{token}', [App\Http\Controllers\Auth\MagicLinkController::class, 'verify'])->name('magic.verify');

Route::middleware(['auth'])->group(function () {
    Route::get('/projects/{project}/brief/export-pdf', [App\Http\Controllers\ProjectBriefExportController::class, 'exportPdf'])->name('projects.brief.export-pdf');
    Route::get('/projects/{project}/brief/export-doc', [App\Http\Controllers\ProjectBriefExportController::class, 'exportDoc'])->name('projects.brief.export-doc');
});
