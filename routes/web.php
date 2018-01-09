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
//For query debugging:
/*
DB::listen(function($query){
    var_dump($query->sql);
});*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/list', 'ListController@index')->name('list');
Route::get('/movie/{id}', 'MovieController@movie')->name('movie');
Route::get('/movie/{mid}/review/{rid}', 'ReviewController@review')->name('review');
Route::get('/person/{pid}', 'PersonController@person')->name('person');
Route::get('/user/{login}', 'UserController@user')->name('user');

