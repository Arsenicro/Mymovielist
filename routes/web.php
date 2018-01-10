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
Route::get('/list', 'ListController@movie')->name('movielist');
Route::get('/movie/{id}', 'MovieController@movie')->name('movie');
Route::get('/movie/{mid}/review/{rid}', 'ReviewController@review')->name('review');
Route::get('/person/{pid}', 'PersonController@person')->name('person');
Route::get('/user/{login}', 'UserController@user')->name('user');

Route::middleware('mod')->group(
    function () {
        Route::get('/movie/{id}/edit', 'MovieController@edit')->name('editMovie');
        Route::post('/movie/{id}/edit/savetitle', 'MovieController@saveTitle')->name('saveTitle');
        Route::post('/movie/{id}/edit/savedesc', 'MovieController@saveDesc')->name('saveDesc');
        Route::post('/movie/{id}/edit/saveimg', 'MovieController@saveImage')->name('saveImage');
        Route::post('/movie/{id}/edit/savedate', 'MovieController@saveDate')->name('saveDate');
        Route::post('/movie/{id}/edit/editrole', 'MovieController@editRole')->name('editRole');
        Route::post('/movie/{id}/edit/newcast', 'MovieController@newCast')->name('newCast');
        Route::post('/movie/{id}/edit/newgenre', 'MovieController@newGenre')->name('newGenre');
        Route::post('/movie/{id}/edit/deletegenre', 'MovieController@deleteGenre')->name('deleteGenre');
    }
);