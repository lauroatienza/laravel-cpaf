<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\MouMoaController;
use App\Http\Controllers\FileViewController;

// Redirect root URL to admin login
Route::get('/', function () {
    return redirect('/cpaf/login');
});

// Document routes
Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
Route::resource('documents', DocumentController::class);

// MOU/MOA routes
Route::get('/mou-moa', [MouMoaController::class, 'create'])->name('mou-moa.create');
Route::post('/mou-moa', [MouMoaController::class, 'store'])->name('mou-moa.store');

// ✅ Route for viewing files
Route::get('/files/{directory}/{filename}', [FileViewController::class, 'show'])->name('files.view');
