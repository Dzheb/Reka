<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
/*
| Web Routes
*/
//delete one task

Route::delete(
    '/task/{id}',
    [TaskController::class, 'deleteTask']
)->middleware('auth')
    ->name('delete-task');

Route::post('/task', [TaskController::class, 'submitTask'])->middleware('auth')->name('post-task');
Route::post('/tag', [TaskController::class, 'submitTag'])->middleware('auth')->name('post-tag');
Route::post('/task/{id}', [TaskController::class, 'updateTask'])->middleware('auth')->name('put-task');

require __DIR__.'/auth.php';
