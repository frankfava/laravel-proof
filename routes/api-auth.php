<?php

use App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/ping', fn () => 'pong')->name('ping');

Route::get('/user', fn (Request $request) => $request->user())->name('user');

// Manage Users
Route::apiResource('users', Api\UserController::class)->names('users');

