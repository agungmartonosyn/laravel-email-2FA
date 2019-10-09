<?php


Route::redirect('/', '/login');

// Auth::routes(['register' => false]);

Auth::routes();

Route::get('verify/resend', 'Auth\TwoFactorController@resend')->name('verify.resend');
Route::resource('verify', 'Auth\TwoFactorController')->only(['index', 'store']);

Route::middleware(['auth', 'twofactor'])->group(function () {

    Route::get('/home', 'HomeController@index')->name('home');

});