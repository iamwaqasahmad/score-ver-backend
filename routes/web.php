<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\CaddyController;
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

Route::get('/caddy-check', 'CaddyController@check');

Route::get('/', function () {
    return view('welcome');
});
