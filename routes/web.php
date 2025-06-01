<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\SsoVerificationController;
use App\Http\Middleware\VerifySSOSession;
use App\Http\Middleware\RedirectIfUnauthenticated;

Route::middleware([RedirectIfUnauthenticated::class])->group(function() {
  Route::get('/', function () {
      return view('welcome');
  })->name('home');


  // Logout
  Route::get('/logout', [LogoutController::class, 'logout'])->name('logout');
});

Route::get('/auth', [SsoVerificationController::class, 'auth'])->middleware(VerifySSOSession::class)->name('auth');
