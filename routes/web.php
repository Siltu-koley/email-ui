<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\DkimController;
use App\Http\Controllers\EmailController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login', [AuthController::class, 'signinpage'])->name('login');
Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/registar', [AuthController::class, 'registar'])->name('registar');
Route::post('/signin', [AuthController::class, 'authenticate'])->name('signin');

Route::middleware('auth')->group(function () {

Route::get('/domains', [DomainController::class, 'domains'])->name('domains');
Route::get('/adddomains', [DomainController::class, 'adddomains'])->name('adddomains');
Route::post('/store-domain', [DomainController::class, 'storeDomain'])->name('store-domain');
Route::get('/verfy_domain/{domain_id}', [DomainController::class, 'verfy_domain']);
Route::get('/emaillist/{domain_id}', [DomainController::class, 'emaillist'])->name('emaillist');
Route::get('/add_mail/{domain_id}', [DomainController::class, 'createMail'])->name('add_mail');
Route::post('/create_mail', [DomainController::class, 'storeMail'])->name('create_mail');
Route::post('/generate-dkim', [DkimController::class, 'generate']);
Route::get('/mailbox/{email_id}', [EmailController::class, 'mailbox'])->name('mailbox');

Route::get('/logout', function () {
    Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    return redirect('/login');
});

});