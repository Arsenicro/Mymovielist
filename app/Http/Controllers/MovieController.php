<?php

namespace Mymovielist\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Mymovielist\EditHistory;
use Mymovielist\Genre;
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
            $liked     = $user->liked($movie);
        }

        return view(
            'movie',
            [
                'userscore' => $userScore ?? "N/A",
                'liked'     => $liked ?? false,
                'info'      => $info,
                'genres'    => $genres,
                'casts'     => $casts,
                'directors' => $directors,
                'writers'   => $writers,
                'reviews'   => $reviews
            ]
        );
    }

    public function list()
    {
        $movies = Movie::getMoviesInfo(['id', 'title', 'score', 'photo', 'prod_date']);
        $movies = ListController::sort(Input::get('order'), Input::get('sortby'), $movies);
        $get    = ListController::get();

        return view(
            'list', [
                'result'     => $movies,
                'search'     => false,
                'movieList'  => true,
                'userList'   => false,
                'personList' => false,
                'get'        => $get
            ]
        );
    }

    public function add()
    {
        $title = Input::get('movieTitle');
        if ($title == "" || Movie::titleExists($title)) {
            return redirect()->back()->with('error', "Something went wrong!");
        }

        $movie = Movie::create(['title' => $title], []);
        return redirect()->route('movie', [$movie->getMovieInfo()->id])->with('message', 'Created!');
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

    public function save($mid)
    {
        $title = true;
        $date  = true;
        $desc  = true;
        $image = true;

        if (Input::get('title') !== Input::get('oldtitle')) {
            $title = $title && $this->saveTitle($mid, Input::get('title'));
        }
        if (Input::get('prod_date') !== Input::get('oldprod_date')) {
            $date = $date && $this->saveDate($mid, Input::get('prod_date'));
        }
        if (Input::get('description') !== Input::get('olddescription')) {
            $desc = $desc && $this->saveDesc($mid, Input::get('description'));
        }
        if (Input::get('image') !== Input::get('oldimage')) {
            $image = $image && $this->saveImage($mid, Input::get('image'));
        }
        if ($title && $date && $desc && $image) {
            return redirect()->back()->with('message', 'Saved');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function saveTitle($mid, $title)
    {
        $movie = new Movie($mid);
        if ($title == '') {
            return false;
        }

        $history = new EditHistory('movie');
        $history->saveEdit($mid, 'title', $movie->getMovieInfo()->title);

        $movie->save(['title' => $title]);
        return true;
    }

    public function saveDesc($mid, $desc)
    {
        $movie = new Movie($mid);
        if ($desc == '') {
            return false;
        }

        $history = new EditHistory('movie');
        $history->saveEdit($mid, 'description', $movie->getMovieInfo()->description);

        $movie->save(['description' => $desc]);
        return true;
    }

    public function saveImage($mid, $img)
    {
        $movie = new Movie($mid);
        if ($img == '') {
            return false;
        }

        $history = new EditHistory('movie');
        $history->saveEdit($mid, 'photo', $movie->getMovieInfo()->photo);

        $movie->save(['photo' => $img]);
        return true;
    }

    public function saveDate($mid, $date)
    {
        $movie = new Movie($mid);
        if (date('Y-m-d', strtotime($date)) == $date) {

            $history = new EditHistory('movie');
            $history->saveEdit($mid, 'date', $movie->getMovieInfo()->prod_date);

            $movie->save(['prod_date' => Carbon::createFromFormat('Y-m-d', $date)]);
            return true;
        }

        return false;
    }

    public function editRole($mid)
    {
        $role     = Input::get('role');
        $personId = Input::get('personid');

        $person = new Person($personId);
        $movie  = new Movie($mid);

        if ($person->saveRole($movie, $role)) {
            return redirect()->back()->with('message', 'Saved');
        }

        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function newCast($mid)
    {
        $role     = Input::get('role');
        $personId = Input::get('personid');

        $person = new Person($personId);
        $movie  = new Movie($mid);
        if ($person->exist()) {
            if ($person->getRole($movie) == null) {
                $movie->newStar($person, $role);
                return redirect()->back()->with('message', 'Saved');
            }
        }

        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function deleteCast($mid)
    {
        $movie  = new Movie($mid);
        $person = new Person(Input::get('pid'));

        if ($movie->exist() && $person->exist()) {
            if ($movie->deleteStar($person)) {
                return redirect()->back()->with('message', 'Saved');
            }
        }

        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function newGenre($mid)
    {
        $movie = new Movie($mid);
        $name  = Input::get('name');
        $genre = new Genre($name);

        if ($movie->exist() && $genre->exist()) {
            if ($movie->saveGenre($genre)) {
                return redirect()->back()->with('message', 'Saved');
            }
        }
        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function deleteGenre($mid)
    {
        $movie = new Movie($mid);
        $name  = Input::get('name');
        $genre = new Genre($name);

        if ($movie->exist() && $genre->exist()) {
            if ($movie->deleteGenre($genre)) {
                return redirect()->back()->with('message', 'Deleted');
            }
        }
        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function newDirector($mid)
    {
        $movie  = new Movie($mid);
        $person = new Person(Input::get('pid'));

        if ($movie->exist() && $person->exist()) {
            if ($movie->directedBy($person)) {
                return redirect()->back()->with('message', 'Saved');
            }
        }
        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function deleteDirector($mid)
    {
        $movie  = new Movie($mid);
        $person = new Person(Input::get('pid'));

        if ($movie->exist() && $person->exist()) {
            if ($movie->deleteDirector($person)) {
                return redirect()->back()->with('message', 'Saved');
            }
        }

        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function newWriter($mid)
    {
        $movie  = new Movie($mid);
        $person = new Person(Input::get('pid'));

        if ($movie->exist() && $person->exist()) {
            if ($movie->wroteBy($person)) {
                return redirect()->back()->with('message', 'Saved');
            }
        }
        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function deleteWriter($mid)
    {
        $movie  = new Movie($mid);
        $person = new Person(Input::get('pid'));

        if ($movie->exist() && $person->exist()) {
            if ($movie->deleteWriter($person)) {
                return redirect()->back()->with('message', 'Saved');
            }
        }

        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function saveScore($mid)
    {
        $score = Input::get('score');
        $movie = new Movie($mid);
        $user  = new User(Auth::user()->login);
        $bool  = true;

        if ($score == "N/A") {
            if ($user->deleteScore($movie)) {
                return redirect()->back()->with('message', 'Saved');
            }
        } elseif ($movie->exist()) {
            if ($user->scored($movie)) {
                $bool = $bool && $user->deleteScore($movie);
            }
            if ($bool && $user->score($movie, $score)) {
                return redirect()->back()->with('message', 'Saved');
            }
        }
        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function likeOrNot($mid)
    {
        $movie = new Movie($mid);
        $user  = new User(Auth::user()->login);

        if ($user->liked($movie)) {
            if ($user->deleteLike($movie)) {
                return redirect()->back()->with('message', 'Deleted');
            }
        }
        if ($user->likeIt($movie)) {
            return redirect()->back()->with('message', 'Liked');
        }

        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function deleteMovie($mid)
    {
        $movie = new Movie($mid);
        if ($movie->exist()) {
            $reviews = $movie->getReviews();
            foreach ($reviews as $review) {
                $rev = new Review($review->rid);
                $rev->delete();
            }
            $movie->delete();
            return redirect()->route('movieList')->with('message', 'Deleted!');
        }

        return redirect()->back()->with('error', 'Something went wrong!');

    }
}

