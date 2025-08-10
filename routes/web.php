<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Semua route aplikasi ada di sini.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * Master Items Routes
 */
Route::get('/master-items', [App\Http\Controllers\MasterItemsController::class, 'index'])->name('master-items.index');
Route::get('/master-items/search', [App\Http\Controllers\MasterItemsController::class, 'search'])->name('master-items.search');
Route::get('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formView'])->name('master-items.form');
Route::post('/master-items/form/{method}/{id?}', [App\Http\Controllers\MasterItemsController::class, 'formSubmit'])->name('master-items.submit');
Route::get('/master-items/view/{kode}', [App\Http\Controllers\MasterItemsController::class, 'singleView'])->name('master-items.view');
Route::delete('/master-items/delete/{id}', [App\Http\Controllers\MasterItemsController::class, 'delete'])->name('master-items.delete');
Route::get('/master-items/update-random-data', [App\Http\Controllers\MasterItemsController::class, 'updateRandomData'])->name('master-items.update-random');

/**
 * Kategori Items Routes
 */
Route::get('/kategori-items', [App\Http\Controllers\KategoriItemsController::class, 'index'])->name('kategori-items.index');
Route::get('/kategori-items/search', [App\Http\Controllers\KategoriItemsController::class, 'search'])->name('kategori-items.search');
Route::get('/kategori-items/form/{method}/{id?}', [App\Http\Controllers\KategoriItemsController::class, 'formView'])->name('kategori-items.form');
Route::post('/kategori-items/form/{method}/{id?}', [App\Http\Controllers\KategoriItemsController::class, 'formSubmit'])->name('kategori-items.submit');
Route::get('/kategori-items/view/{id}', [App\Http\Controllers\KategoriItemsController::class, 'singleView'])->name('kategori-items.view');
Route::delete('/kategori-items/delete/{id}', [App\Http\Controllers\KategoriItemsController::class, 'delete'])->name('kategori-items.delete');
