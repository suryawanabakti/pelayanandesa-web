<?php

use App\Http\Controllers\ChartExportController;
use App\Http\Controllers\UsersImportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::redirect('/admin/login', '/login');
Route::redirect('/dashboard', '/admin');
Route::redirect('/pimpinan/login', '/login');

Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('guest');


Route::get('/users/template', [UsersImportController::class, 'template'])->name('users.template');
Route::get('/users/export', [UsersImportController::class, 'export'])->name('users.export');

Route::get('/export/chart-excel', [ChartExportController::class, 'exportExcel'])->name('export.chart.excel');
Route::get('/export/chart-detailed', [ChartExportController::class, 'exportDetailedReport'])->name('export.chart.detailed');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (auth()->attempt($credentials)) {
        return redirect()->intended('/admin');
    }
    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
})->middleware('guest')->name('login.post');
