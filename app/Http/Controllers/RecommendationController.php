<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Mymovielist\Movie;
use Mymovielist\User;

class RecommendationController extends Controller
{
    public function list()
    {
        $user       = new User(Auth::user()->login);
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

    public function delete($mid)
    {

        $user  = new User(Auth::user()->login);
        $movie = new Movie($mid);
        $user->dislikeMovie($movie);

        return redirect()->route('home')->with('message', 'Removed from recommendations!');
    }

    public function reset()
    {
        $user = new User(Auth::user()->login);
        $user->resetDisliked();

        return redirect()->route('home')->with('message', 'Restarted recommendations!');
    }
}
