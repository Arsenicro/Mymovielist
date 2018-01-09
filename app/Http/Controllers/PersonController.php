<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;
use Mymovielist\Movie;
use Mymovielist\Person;

class PersonController extends Controller
{
    public function person($pid)
    {
        $person     = new Person($pid);

        if(!$person->exist())
            abort(404);

        $personInfo = $person->getPersonInfo();
        $played     = $person->played();
        $directed   = $person->directed();
        $wrote      = $person->wrote();

        $played = $played->map(
            function ($key) use ($person) {
                $movie = new Movie($key->mid);
                $role  = $person->getRole($movie);
                return ['info' => $movie->getMovieInfo(), 'role' => $role];
            }
        );

        $directed = $directed->map(
            function ($key) use ($person) {
                $movie = new Movie($key->mid);
                return ['info' => $movie->getMovieInfo()];
            }
        );

        $wrote = $wrote->map(
            function ($key) use ($person) {
                $movie = new Movie($key->mid);
                return ['info' => $movie->getMovieInfo()];
            }
        );

        return view(
            'person', [
            'info'     => $personInfo,
            'played'   => $played,
            'directed' => $directed,
            'wrote'    => $wrote
        ]
        );

    }
}
