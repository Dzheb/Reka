<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', function () {
    $user = Auth::user();
    return view('home',['user' => $user]);
})->name('welcome');

Route::get('/home', function () {
    $user = Auth::user();
    return view('home',['user' => $user]);
})->name('home');

Route::get('/list
_add', function () {
    return view('add-list',['uid' => Auth::user()->id]);
})->middleware('auth')->name('add-list');

require __DIR__.'/auth.php';
require __DIR__.'/list.php';
require __DIR__.'/task.php';

