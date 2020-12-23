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


Route::get('/login', 'Admin\Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Admin\Auth\LoginController@login');
Route::post('/logout', 'Admin\Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => ['admin_auth']], function (){
    Route::get('/', 'Admin\HomeController@index')->name('home');
    Route::get('/change_password', 'Admin\AdminUserController@change_password')->name('change_password');
    Route::post('/change_password', 'Admin\AdminUserController@change_password');
});

Route::group(['middleware' => ['role:SuperAdmin,admins']], function () {
    Route::resource('adminRoles', 'Admin\AdminRoleController');
    Route::resource('adminPermissions', 'Admin\AdminPermissionController');

    Route::get('/firstRun', 'Admin\PermissionController@firstRun');
    Route::get('users/{user}/reset_password', 'Admin\AdminUserController@reset_password')->name('reset_user_password');
});

Route::group(['middleware' => ['admin_auth']], function (){
    Route::resource('adminUsers', 'Admin\AdminUserController')->middleware('userCan:AdminUsers');
    Route::resource('users', 'Admin\UserController')->middleware('userCan:Users');
    Route::resource('roles', 'Admin\RoleController')->middleware('userCan:Roles');
    Route::resource('permissions', 'Admin\PermissionController')->middleware('userCan:Permissions');
    Route::resource('tags', 'Admin\TagController')->middleware('userCan:Tags');
    Route::resource('documents', 'Admin\DocumentController')->middleware('userCan:Documents');
    Route::get('config', 'Admin\ConfigController@edit')->name('config.edit')->middleware('userCan:Config');
    Route::patch('config', 'Admin\ConfigController@update')->name('config.update')->middleware('userCan:Config');
    Route::get('activities', 'Admin\LogController@index')->name('activities.index')->middleware('userCan:Log');
});
