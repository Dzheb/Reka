<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListController;
/*
| Company Web Routes
*/

//get one list view
Route::get(
    '/list/{id}/view',
    [ListController::class, 'showOneList']
)->middleware('auth')
    ->name('list-data-one');

//delete list
Route::delete(
    '/list/{id}',
    [ListController::class, 'deleteList']
)->middleware('auth')
    ->name('list-delete');

//save list
Route::put(
    '/list/{id}',
    [ListController::class, 'updateListSubmit']
)->middleware('auth')
    ->name('list-update-submit');
//  get all lists
Route::get('/list', [ListController::class, 'allLists'])->middleware('auth')->name('lists');
// save one list to DB
Route::post('/list', [ListController::class, 'saveNewList'])->middleware('auth')->name('save-new-list');

