<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Mymovielist\Movie;
use Mymovielist\NEO4J\NEO4JUser;
use Mymovielist\User;

class HomeController extends Controller
{
    public function index()
    {
        $loggedIn = Auth::user() != null;

        if (!$loggedIn) {
            return redirect()->route('movieList');
        }

        $user = new User(Auth::user()->login);

        $recommends = $user->recommend();

        $recommends = $recommends->map(
            function ($key) {
                $movie = new Movie($key->mid);
                return $movie->getMovieInfo();
            }
        );

        return view(
            'home',
            [
                'recommends' => $recommends,
            ]
        );
    }

    public function deleteRecommend($mid)
    {

        $user  = new User(Auth::user()->login);
        $movie = new Movie($mid);
        $user->dislikeMovie($movie);

        return redirect()->route('home')->with('message', 'Removed from recommendations!');
    }

    public function resetRecommend()
    {
        $user = new User(Auth::user()->login);
        $user->resetDisliked();

        return redirect()->route('home')->with('message', 'Restarted recommendations!');
    }

    public function query()
    {
        $results = NEO4JUser::myQuery(Auth::user()->login);
        $results = $results->map(
            function ($key) {
                return $key->getMovieInfo();
            }
        );

        return view('query', ['movies' => $results]);
    }
}
