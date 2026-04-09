<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('users/create', [FormController::class, 'create']);
Route::post('users/create', [FormController::class, 'store'])->name('users.store');
Route::get('users/list', [FormController::class, 'list']);
Route::delete('users/{id}', [FormController::class, 'delete'])->name('users.delete');
