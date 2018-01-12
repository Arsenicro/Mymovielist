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

Route::get(
    '/', function () {
    return view('welcome');
}
);

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/movielist', 'ListController@movie')->name('movieList');
Route::get('/personlist', 'ListController@person')->name('personList');
Route::get('/userlist', 'ListController@user')->name('userList');
Route::get('/movie/{id}', 'MovieController@movie')->name('movie');
Route::get('/movie/{mid}/review/{rid}', 'ReviewController@review')->name('review');
Route::get('/person/{pid}', 'PersonController@person')->name('person');
Route::get('/user/{login}', 'UserController@user')->name('user');
Route::get('/search', 'SearchController@search')->name('search');

Route::middleware('mod')->group(
    function () {
        //Edit movie
        Route::get('/movie/{id}/edit', 'MovieController@edit')->name('editMovie');
        Route::post('/movie/{id}/edit/savetitle', 'MovieController@saveTitle')->name('saveTitle');
        Route::post('/movie/{id}/edit/savedesc', 'MovieController@saveDesc')->name('saveDesc');
        Route::post('/movie/{id}/edit/saveimg', 'MovieController@saveImage')->name('saveImage');
        Route::post('/movie/{id}/edit/savedate', 'MovieController@saveDate')->name('saveDate');
        Route::post('/movie/{id}/edit/editrole', 'MovieController@editRole')->name('editRole');
        Route::post('/movie/{id}/edit/newcast', 'MovieController@newCast')->name('newCast');
        Route::post('/movie/{id}/edit/deletecast', 'MovieController@deleteCast')->name('deleteCast');
        Route::post('/movie/{id}/edit/newgenre', 'MovieController@newGenre')->name('newGenre');
        Route::post('/movie/{id}/edit/deletegenre', 'MovieController@deleteGenre')->name('deleteGenre');
        Route::post('/movie/{id}/edit/newdirector', 'MovieController@newDirector')->name('newDirector');
        Route::post('/movie/{id}/edit/deletedirector', 'MovieController@deleteDirector')->name('deleteDirector');
        Route::post('/movie/{id}/edit/newwriter', 'MovieController@newWriter')->name('newWriter');
        Route::post('/movie/{id}/edit/deletewriter', 'MovieController@deleteWriter')->name('deleteWriter');
        Route::get('/movie/{id}/edit/deletemovie', 'MovieController@deleteMovie')->name('deleteMovie');

        //Edit person
        Route::get('/person/{id}/edit', 'PersonController@edit')->name('editPerson');
        Route::post('/person/{id}/edit/savetitle', 'PersonController@saveName')->name('saveName');
        Route::post('/person/{id}/edit/savesurname', 'PersonController@saveSurname')->name('saveSurname');
        Route::post('/person/{id}/edit/savebiography', 'PersonController@saveBiography')->name('saveBiography');
        Route::post('/person/{id}/edit/savebirthday', 'PersonController@saveBirthday')->name('saveBirthday');
        Route::post('/person/{id}/edit/saveimage', 'PersonController@saveImage')->name('saveImage');
        Route::post('/person/{id}/edit/deleteperson', 'PersonController@deletePerson')->name('deletePerson');

        //Adding
        Route::get('/adding', 'AddingController@index')->name('adding');
        Route::post('/adding/addperson', 'AddingController@addPerson')->name('addingPerson');
        Route::post('/adding/addmovie', 'AddingController@addMovie')->name('addingMovie');
        Route::post('/adding/addgenre', 'AddingController@addGenre')->name('addingGenre');
        Route::post('/adding/deletegenre', 'AddingController@deleteGenre')->name('deleteGenre');
    }
);

Route::middleware('authModOrMe')->group(
    function () {
        //Edit user
        Route::get('/user/{login}/edit', 'UserController@edit')->name('editUser');
        Route::post('/user/{login}/edit/savename', 'UserController@saveName')->name('saveName');
        Route::post('/user/{login}/edit/savesurname', 'UserController@saveSurname')->name('saveSurname');
        Route::post('/user/{login}/edit/saveavatar', 'UserController@saveAvatar')->name('saveAvatar');
        Route::post('/user/{login}/edit/savebirthday', 'UserController@saveBirthday')->name('saveBirthday');
        Route::post('/user/{login}/edit/saveabout', 'UserController@saveAbout')->name('saveAbout');
        Route::post('/user/{login}/edit/savelocation', 'UserController@saveLocation')->name('saveLocation');
        Route::post('/user/{login}/edit/savegender', 'UserController@saveGender')->name('saveGender');

    }
);

Route::middleware('auth')->group(
    function () {
        //Movie
        Route::post('/movie/{id}/edit/savescore', 'MovieController@saveScore')->name('saveScore');
        Route::post('/movie/{id}/edit/likeornot', 'MovieController@likeOrNot')->name('likeOrNot');
        Route::get('/movie/{id}/newreview', 'ReviewController@newReview')->name('newReview');
        Route::post('/movie/{id}/createreview', 'ReviewController@createReview')->name('createReview');

        //Person
        Route::post('/person/{id}/edit/likeornot', 'PersonController@likeOrNot')->name('likeOrNot');

        //User
        Route::post('/user/{login}/edit/followornot', 'UserController@followOrNot')->name('followOrNot');

    }
);

Route::middleware('admin')->group(
    function () {
        Route::post('/user/{login}/edit/saveaccess', 'UserController@saveAccess')->name('saveAccess');
        Route::post('/user/{login}/edit/deleteuser', 'UserController@deleteUser')->name('deleteUser');
    }
);
