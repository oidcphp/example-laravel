<?php

use App\Http\Controllers\Line\Callback as LineCallback;
use App\Http\Controllers\Line\Login as LineLogin;
use App\Http\Controllers\SignInWithApple\Callback as SignInWithAppleCallback;
use App\Http\Controllers\SignInWithApple\Login as SignInWithAppleLogin;
use Illuminate\Support\Facades\Route;

Route::get('/line/login', LineLogin::class);
Route::get('/line/callback', LineCallback::class);

Route::get('/sign-in-with-apple/login', SignInWithAppleLogin::class);
Route::post('/sign-in-with-apple/callback', SignInWithAppleCallback::class);
