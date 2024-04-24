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

require __DIR__.'/auth.php';
