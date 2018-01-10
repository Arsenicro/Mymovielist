<?php

namespace Mymovielist\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
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

    public function edit($id)
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
            'editmovie',
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

    public function saveTitle($mid)
    {
        $title = Input::get('title');
        $movie = new Movie($mid);
        if ($title == '') {
            return redirect()->back()->with('error', 'Not a valid title');
        }
        $movie->save(['title' => $title]);
        return redirect()->back()->with('message', 'Saved');
    }

    public function saveDesc($mid)
    {
        $desc  = Input::get('desc');
        $movie = new Movie($mid);
        if ($desc == '') {
            return redirect()->back()->with('error', 'Not a valid description');
        }
        $movie->save(['description' => $desc]);
        return redirect()->back()->with('message', 'Saved');
    }

    public function saveImage($mid)
    {
        $img   = Input::get('img');
        $movie = new Movie($mid);
        if ($img == '') {
            return redirect()->back()->with('error', 'Not a valid image link');
        }
        $movie->save(['image' => $img]);
        return redirect()->back()->with('message', 'Saved');
    }

    public function saveDate($mid)
    {
        $movie = new Movie($mid);
        $date  = Input::get('date');
        if (date('Y-m-d', strtotime($date)) == $date) {
            $movie->save(['prod_date' => Carbon::createFromFormat('Y-m-d', $date)]);
            return redirect()->back()->with('message', 'Saved');
        }

        return redirect()->back()->with('error', 'Not a valid date');
    }

    public function editRole($mid)
    {
        $role     = Input::get('role');
        $personId = Input::get('personid');

        $person = new Person($personId);
        $movie  = new Movie($mid);

        if($person->saveRole($movie, $role))
        {
            return redirect()->back()->with('message', 'Saved');
        }

        return redirect()->back()->with('error', 'Something went wrong!');
    }
}
