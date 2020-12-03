<?php

use Illuminate\Support\Facades\Route;

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

App::setLocale('vi');
//Auth::routes(['verify' => true]);

//Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
//Route::post('/login', 'Auth\LoginController@login');
//Route::post('/logout', 'Auth\LoginController@logout')->name('logout');



Route::get('/', function (){
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/login', 'LoginController@showLoginForm')->name('login');
Route::post('/login', 'LoginController@login');
Route::post('/logout', 'LoginController@logout')->name('logout');

Route::group(['middleware' => ['auth']], function (){
    Route::get('/change_password', 'UserController@change_password')->name('change_password');
    Route::post('/change_password', 'UserController@change_password');
});
