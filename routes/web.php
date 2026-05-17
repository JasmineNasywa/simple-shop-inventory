<?php

use Illuminate\Support\Facades\Route;
use Filament\Http\Controllers\Auth\LoginController;


// Pancing rute POST agar muncul di sistem
Route::post('/admin/login', function () {
    return app(\Filament\Http\Responses\Auth\LoginResponse::class);
})->name('filament.admin.auth.login');
Route::get('/', function () {
    return view('welcome');
});
