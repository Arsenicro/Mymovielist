<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;
use Mymovielist\Movie;
use Mymovielist\Person;
use Mymovielist\Review;
use Mymovielist\User;
use function PHPSTORM_META\map;

class MovieController extends Controller
{
    public function movie($id)
    {
        $movie     = new Movie($id);
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

        return view(
            'movie',
            [
                'info'      => $info,
                'genres'    => $genres,
                'casts'     => $casts,
                'directors' => $directors,
                'writers'   => $writers,
                'reviews'   => $reviews
            ]
        );
    }

    public function review($mid, $rid)
    {
        $review = new Review($rid);

        if ($review->getMovie()->mid != $mid) {
            abort(404);
        }

        $movie      = new Movie($mid);
        $movieInfo  = $movie->getMovieInfo();
        $user       = $review->getAuthor();
        $user       = new User($user->login);
        $userInfo   = $user->getUserInfo();
        $reviewInfo = $review->getReviewInfo();

        return view(
            'review',
            [
                'movieinfo'  => $movieInfo,
                'userinfo'   => $userInfo,
                'reviewinfo' => $reviewInfo
            ]
        );
    }
}
