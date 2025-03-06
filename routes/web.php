<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/admin/login');
});


use App\Http\Controllers\DocumentController;

Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
Route::resource('documents', DocumentController::class);
Route::get('/mou-moa', [MouMoaController::class, 'create'])->name('mou-moa.create');
Route::post('/mou-moa', [MouMoaController::class, 'store'])->name('mou-moa.store');