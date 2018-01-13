<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Mymovielist\Genre;
use Mymovielist\Movie;
use Mymovielist\Person;
use Mymovielist\SearchStats;
use Mymovielist\User;

class SearchController extends Controller
{
    public function search()
    {
        $text         = Input::get('search');
        $genres       = Input::get('genres');
        $allGenres    = Genre::getAllGenres();
        $watched      = Input::get('watched') == "on";
        $searchMovie  = Input::get('searching') == "movie";
        $searchUser   = Input::get('searching') == "user";
        $searchPeople = Input::get('searching') == "people";
        $result       = null;

        $get   = "";
        $input = Input::get();
        foreach ($input as $i => $item) {
            if ($i !== "sortby" && $i !== "order") {
                $get .= "&" . $i . "=" . $item;
            }
        }

        if ($genres == null && $text == null) {
            return view(
                'list', [
                    'result'     => '',
                    'genres'     => $allGenres,
                    'inputTitle' => null,
                    'inputGenre' => null,
                    'watched'    => $watched,
                    'search'     => true,
                    'userList'   => false,
                    'movieList'  => false,
                    'personList' => false,
                    'get'        => $get
                ]
            );

        }

        $searchStats = new SearchStats();

        if ($searchMovie) {
            $searchStats->saveSearch($text, 'movie', $watched, $genres);
            $result = $this->searchMovie($text, $genres, $watched);
        } elseif ($searchUser) {
            $searchStats->saveSearch($text, 'user');
            $result = $this->searchUser($text);
        } elseif ($searchPeople) {
            $searchStats->saveSearch($text, 'person');
            $result = $this->searchPerson($text);
        } else {
            return redirect('/search')->with('error', 'Something went wrong');
        }

        $result = ListController::sort(Input::get('order'), Input::get('sortby'), $result);

        return view(
            'list',
            [
                'genres'     => $allGenres,
                'result'     => $result,
                'inputTitle' => $text,
                'inputGenre' => $genres,
                'search'     => true,
                'watched'    => $watched,
                'movieList'  => $searchMovie,
                'userList'   => $searchUser,
                'personList' => $searchPeople,
                'get'        => $get
            ]
        );
    }

    private function searchMovie($title, $genres, $watched)
    {
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
            $movies = $movies->filter(
                function ($key) {
                    $movie = new Movie($key->id);
                    $user  = new User(Auth::user()->login);
                    return !$user->watchedMovie($movie);
                }
            );
        }

        return $movies;
    }

    private function searchPerson($text)
    {
        if ($text == "" || $text == null) {
            $persons = Person::getPersonsInfo();
        } else {
            $persons = Person::getPersonsInfo()->filter(
                function ($key) use ($text) {
                    return strpos($key->name . " " . $key->surname, $text) !== false;
                }
            );
        }

        $persons = $persons->map(
            function ($key) {
                $person    = new Person($key->id);
                $key->fans = $person->numberOfFans();
                return $key;
            }
        );

        return $persons;
    }

    private function searchUser($login)
    {
        if ($login == "" || $login == null) {
            $users = User::getUsersInfo(['login', 'avatar']);
        } else {
            $users = User::getUsersInfo(['login', 'avatar'])->filter(
                function ($key) use ($login) {
                    return strpos($key->login, $login) !== false;
                }
            );
        }

        return $users;
    }
}
