<?php

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

Route::get('/', function () {
    return view('welcome');
});

//登录
Route::post('login','Index\IndexController@login');
Route::get('login','Index\IndexController@loginView');
Route::post('apiLogin','Index\IndexController@apiLogin');

//注册
Route::post('register','Index\IndexController@register');
Route::get('register','Index\IndexController@registerView');
Route::post('apiRegister','Index\IndexController@apiRegister');



