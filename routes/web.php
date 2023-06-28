<?php

use App\Http\Controllers\ConversionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ConversionController::class, 'index'])->name('convert.index');
Route::get('/number', [ConversionController::class, 'number'])->name('convert.number');
Route::get('/words', [ConversionController::class, 'words'])->name('convert.words');
Route::post('/convert-num-to-word', [ConversionController::class, 'convertNumberToWords'])->name('convert.convertNumber');
Route::post('/convert-word-to-num', [ConversionController::class, 'convertWordsToNumber'])->name('convert.convertWords');
Route::post('/convert-currency', [ConversionController::class, 'currencyConversion'])->name('convert.convertCurrency');
