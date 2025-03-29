<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', function () {
    return redirect('/admin/login');
});

Route::redirect('/pimpinan/login', '/admin/login')->name('login');
