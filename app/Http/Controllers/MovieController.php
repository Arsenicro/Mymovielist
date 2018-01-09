<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mymovielist\Movie;
use Mymovielist\Person;
use Mymovielist\Review;
use Mymovielist\User;

class MovieController extends Controller
{
    public function movie($id)
    {
        $movie = new Movie($id);

        if (!$movie->exist()) {
            abort(404);
        }

        $info      = $movie->getMovieInfo();
        $genres    = $movie->getGenres();
        $casts     = $movie->getStars();
        $directors = $movie->getDirectors();
        $writers   = $movie->getWriters();
        $reviews   = $movie->getReviews();

        $reviews = $reviews->map(
            function ($key) {
                $review = new Review($key->rid);
                $user   = $review->getAuthor();
                $user   = new User($user->login);
                $user   = $user->getUserInfo();
                $info   = $review->getReviewInfo();

                return ['info' => $info, 'user' => $user];
            }
        );

        $casts = $casts->map(
            function ($key) use ($movie) {
                $person = new Person($key->pid);
                return ['info' => $person->getPersonInfo(), 'role' => $person->getRole($movie)];
            }
        );

        $directors = $directors->map(
            function ($key) use ($movie) {
                $person = new Person($key->pid);
                return ['info' => $person->getPersonInfo()];
            }
        );

        $writers = $writers->map(
            function ($key) use ($movie) {
                $person = new Person($key->pid);
                return ['info' => $person->getPersonInfo()];
            }
        );
        if (Auth::user() != null) {
            $user      = new User(Auth::user()->login);
            $userScore = $user->getUserScore($movie);
        }

        return view(
            'movie',
            [
                'userscore' => $userScore ?? "N/A",
                'info'      => $info,
                'genres'    => $genres,
                'casts'     => $casts,
                'directors' => $directors,
                'writers'   => $writers,
                'reviews'   => $reviews
            ]
        );
    }
}
