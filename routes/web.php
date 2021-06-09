<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/home', [App\Http\Controllers\HomeController::class, 'store'])->name('uploadFile');
//Route::get('/files/{filename}', [App\Http\Controllers\HomeController::class, 'downloadFile'])->name('downloadFile');
Route::get('/encrypt', [App\Http\Controllers\HomeController::class, 'encrypt'])->name('enc');

Route::get('/decrypt-home', [App\Http\Controllers\HomeController::class, 'decrypthome'])->name('dec-home');
Route::post('/decrypt-home', [App\Http\Controllers\HomeController::class, 'retrive'])->name('dec.home.post');
Route::get('files/{file}', [App\Http\Controllers\HomeController::class, 'download'])->name('download');
Route::get('files_iv/{file}', [App\Http\Controllers\HomeController::class, 'downloadIv'])->name('iv_download');
