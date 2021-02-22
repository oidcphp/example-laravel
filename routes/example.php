<?php

use App\Http\Controllers\Line\Callback as LineCallback;
use App\Http\Controllers\Line\Login as LineLogin;
use Illuminate\Support\Facades\Route;

Route::get('/line/login', LineLogin::class);
Route::get('/line/callback', LineCallback::class);
