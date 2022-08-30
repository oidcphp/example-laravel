<?php

use App\Http\Controllers\Line\Callback as LineCallback;
use App\Http\Controllers\Line\Login as LineLogin;
use App\Http\Controllers\Office365\Callback as Office365Callback;
use App\Http\Controllers\Office365\Login as Office365Login;
use App\Http\Controllers\SignInWithApple\Callback as SignInWithAppleCallback;
use App\Http\Controllers\SignInWithApple\Login as SignInWithAppleLogin;
use Illuminate\Support\Facades\Route;

Route::get('/line/login', LineLogin::class);
Route::get('/line/callback', LineCallback::class);

Route::get('/office365/login', Office365Login::class);
Route::get('/office365/callback', Office365Callback::class);

Route::get('/sign-in-with-apple/login', SignInWithAppleLogin::class);
Route::post('/sign-in-with-apple/callback', SignInWithAppleCallback::class);
