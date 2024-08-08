<?php

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Murdercode\TinymceEditor\Http\Middleware\TinymceMiddleware;

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

Route::post('/upload', [\App\Http\Controllers\Third\UploadController::class, 'upload'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->middleware(TinymceMiddleware::class);
