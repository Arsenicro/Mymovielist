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
    return redirect(route('home'));
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
        Route::post('/movie/{id}/edit/save', 'MovieController@save')->name('saveMovie');
        Route::post('/movie/{id}/edit/newcast', 'MovieController@newCast')->name('newCast');
        Route::post('/movie/{id}/edit/deletecast', 'MovieController@deleteCast')->name('deleteCast');
        Route::post('/movie/{id}/edit/newgenre', 'MovieController@newGenre')->name('newGenre');
        Route::post('/movie/{id}/edit/deletegenre', 'MovieController@deleteGenre')->name('deleteGenre');
        Route::post('/movie/{id}/edit/newdirector', 'MovieController@newDirector')->name('newDirector');
        Route::post('/movie/{id}/edit/deletedirector', 'MovieController@deleteDirector')->name('deleteDirector');
        Route::post('/movie/{id}/edit/newwriter', 'MovieController@newWriter')->name('newWriter');
        Route::post('/movie/{id}/edit/deletewriter', 'MovieController@deleteWriter')->name('deleteWriter');
        Route::post('/movie/{id}/edit/newrole', 'MovieController@newRole')->name('newRole');
        Route::post('/movie/{id}/edit/deleterole', 'MovieController@deleteRole')->name('deleteRole');
        Route::get('/movie/{id}/edit/deletemovie', 'MovieController@deleteMovie')->name('deleteMovie');

        //Edit person
        Route::get('/person/{id}/edit', 'PersonController@edit')->name('editPerson');
        Route::post('/person/{id}/edit/saveperson', 'PersonController@save')->name('savePerson');
        Route::get('/person/{id}/edit/deleteperson', 'PersonController@deletePerson')->name('deletePerson');

        //Adding
        Route::get(
            '/adding', function () {
            return view('adding');
        }
        )->name('adding');
        Route::post('/person/add', 'PersonController@add')->name('addingPerson');
        Route::post('/movie/add', 'MovieController@add')->name('addingMovie');
        Route::post('/genre/add', 'GenreController@add')->name('addingGenre');
        Route::post('/genre/delete', 'GenreController@delete')->name('deleteGenre');

        //DeleteReview
        Route::post('/movie/{mid}/review/{rid}', 'ReviewController@deleteReview')->name('deleteReview');
    }
);

Route::middleware('authAdminOrMe')->group(
    function () {
        //Edit user
        Route::get('/user/{login}/edit', 'UserController@edit')->name('editUser');
        Route::post('/user/{login}/edit/save', 'UserController@save')->name('save');
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
        Route::post('/person/{id}/edit/likeornot', 'PersonController@likeOrNot')->name('likePersonOrNot');

        //User
        Route::post('/user/{login}/edit/followornot', 'UserController@followOrNot')->name('followOrNot');

        //Recommendation
        Route::get('/deleterecommend/{id}', 'HomeController@deleteRecommend')->name('deleteRecommendation');
        Route::get('/resetrecommend', 'HomeController@resetRecommend')->name('resetRecommend');

        //Query
        Route::get('/listofspecialmovies', 'HomeController@query')->name('query');

    }
);

Route::middleware('admin')->group(
    function () {
        Route::get('/logs', 'LogController@log')->name('logs');
        Route::get('/logs/movie', 'LogController@viewMoviesEditHistory')->name('moviesEdits');
        Route::get('/logs/user', 'LogController@viewUsersEditHistory')->name('usersEdits');
        Route::get('/logs/person', 'LogController@viewPersonsEditHistory')->name('personsEdits');
        Route::get('/logs/search', 'LogController@viewSearch')->name('searchStats');
        Route::post('/logs/clear', 'LogController@clear')->name('clearLogs');
        Route::get('/user/{login}/edit/deleteuser', 'UserController@deleteUser')->name('deleteUser');
    }
);
