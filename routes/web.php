<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\DkimController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Ovhcontroller;
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
Route::post('/update_route', [DomainController::class, 'updateRoute']);
Route::get('/verfy_domain/{domain_id}', [DomainController::class, 'verfy_domain']);
Route::get('/emaillist/{domain_id}', [DomainController::class, 'emaillist'])->name('emaillist');
Route::get('/add_mail/{domain_id}', [DomainController::class, 'createMail'])->name('add_mail');
Route::post('/create_mail', [DomainController::class, 'storeMail'])->name('create_mail');
Route::post('/generate-dkim', [DkimController::class, 'generate']);
Route::get('/mailbox/{email_id}', [EmailController::class, 'mailbox'])->name('mailbox');
Route::post('/sendmail', [EmailController::class, 'sendmail'])->name('sendmail');
Route::get('/emailconfig/{email_id}', [EmailController::class, 'emailconfig']);
Route::post('/add_routing_ip', [EmailController::class, 'add_routing_ip']);
Route::post('/remove_routing_ip', [EmailController::class, 'remove_routing_ip']);
Route::get('/getzoneip/{zone_id}', [DomainController::class, 'getzoneip'])->name('getzoneip');
Route::get('/get_domain/{domain_id}', [DomainController::class, 'get_domain']);
Route::delete('/delete_domain/{domain_id}', [DomainController::class, 'deleteDomain']);
Route::get('/zones-ip', [EmailController::class, 'ZonesIp']);
Route::post('/update_smtp_pass', [EmailController::class, 'update_smtp_pass']);
Route::post('/add-filter', [EmailController::class, 'addFilter']);
Route::post('/edit-filter', [EmailController::class, 'editFilter']);
Route::get('/filter', [EmailController::class, 'filter']);
Route::delete('/delete_filter/{filter_id}', [EmailController::class, 'deleteFilter']);
Route::delete('/delete_mail/{email_id}', [EmailController::class, 'deleteMail']);
Route::post('/update_pass', [AuthController::class, 'update_pass']);

// OVH API

Route::get('/ovh/attached_additional_ip_to_vm', [Ovhcontroller::class,'attached_additional_ip_to_vm'])->name('attached_additional_ip_to_vm');
Route::get('/ovh/additonal_ips', [Ovhcontroller::class,'get_all_additonal_ips'])->name('get_all_additonal_ips');

// OVH API End

Route::get('/logout', function () {
    Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    return redirect('/login');
});

});
