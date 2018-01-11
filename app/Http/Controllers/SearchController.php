<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Mymovielist\Genre;
use Mymovielist\Movie;
use Mymovielist\User;

class SearchController extends Controller
{
    public function search()
    {
        $title     = Input::get('title');
        $genres    = Input::get('genres');
        $allGenres = Genre::getAllGenres();
        $watched   = Input::get('watched') != null;

        if ($genres == null && $title == null) {
            return view('search', ['genres' => $allGenres, 'inputTitle' => null, 'inputGenre' => null, 'searched' => false, 'watched' => $watched]);

        }
        if ($title == "" || $title == null) {
            $movies = Movie::getMoviesInfo(['id', 'title', 'score', 'photo', 'prod_date']);
        } else {
            $movies = Movie::getMoviesInfo(['id', 'title', 'score', 'photo', 'prod_date'])->filter(
                function ($key) use ($title) {
                    return strpos($key->title, $title) !== false;
                }
            );
        }
        if ($genres != "All") {
            $movies = $movies->filter(
                function ($key) use ($genres) {
                    $genre = new Genre($genres);
                    $movie = new Movie($key->id);
                    return $movie->getGenres()->contains($genre->getNeo4jGenre());
                }
            );
        }

        if (Auth::user() != null && !$watched) {
            $movies  = $movies->filter(
                function ($key) {
                    $movie = new Movie($key->id);
                    $user  = new User(Auth::user()->login);
                    return !$user->watchedMovie($movie);
                }
            );
        }

        if (Input::get('order') == 'asc') {
            $movies = $movies->sortBy(Input::get('sortby'));
        } elseif (Input::get('order') == 'desc') {
            $movies = $movies->sortByDesc(Input::get('sortby'));
        }

        return view(
            'search',
            ['genres' => $allGenres, 'movies' => $movies, 'inputTitle' => $title, 'inputGenre' => $genres, 'searched' => true, 'watched' => $watched]
        );
    }
}
