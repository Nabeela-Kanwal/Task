<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('crud/index', [TaskController::class, 'index'])->name('Task.index');
Route::get('crud/create', [TaskController::class, 'create'])->name('Task.create');
Route::post('crud/store', [TaskController::class, 'store'])->name('store');

Route::get('crud/edit', [TaskController::class, 'edit'])->name('Task.edit');
Route::put('tasks/{id}', [TaskController::class, 'update'])->name('Task.update');
Route::delete('tasks/{id}', [TaskController::class, 'destroy'])->name('destroy');



