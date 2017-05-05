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

Route::get('/user/', function () {
    $users = DB::select('select * from food_info_users where 1',[1]);
    $user = "hello";
    $url = url('/');
    return view('/user/user',['users' => $user]);
});
