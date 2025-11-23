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

Route::controller(\NovinVision\MultiStepLogin\Controllers\AuthenticateController::class)->middleware([
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \NovinVision\MultiStepLogin\Middlewares\HandleNextUrl::class,
    'web',
    'guest',
])->group(function () {
    Route::get('login', 'index')->name('login');
    Route::post('login', 'username');

    Route::get('password', 'password')->name('login.password');
    Route::post('password', 'loginPassword');

    Route::get('register', 'register')->name('register');
    Route::post('register', 'store');

    Route::get('forget-password', 'forgetPassword')->name('forget-password');
    Route::post('forget-password', 'requestForgetPassword');
});

Route::controller(\NovinVision\MultiStepLogin\Controllers\VerifyController::class)->middleware([
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \NovinVision\MultiStepLogin\Middlewares\HandleNextUrl::class,
    \NovinVision\MultiStepLogin\Middlewares\AuthenticateNonVerify::class,
    'web',
    'auth',
])->prefix('verify/{field?}')->group(function () {
    Route::get('', 'index')->name('verify');
    Route::post('', 'verify');
    Route::get('resend', 'resend')->name('verify.resend');
});

