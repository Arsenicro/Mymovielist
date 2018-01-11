<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mymovielist\Movie;
use Mymovielist\Person;
use Mymovielist\User;

class ListController extends Controller
{
    public static function sort($order, $sort, $collection)
    {
        if ($order == 'asc') {
            return $collection->sortBy($sort);
        }
        if ($order == 'desc') {
            return $collection->sortByDesc($sort);
        }
        return $collection;
    }

    private function get()
    {
        $get    = "";
        $input  = Input::get();
        foreach ($input as $i => $item) {
            if ($i !== "sortby" && $i !== "order") {
                $get .= "&" . $i . "=" . $item;
            }
        }

        return $get;
    }

    public function movie()
    {
        $movies = Movie::getMoviesInfo(['id', 'title', 'score', 'photo', 'prod_date']);
        $movies = ListController::sort(Input::get('order'), Input::get('sortby'), $movies);

        $get = $this->get();

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

    public function person()
    {
        $persons = Person::getPersonsInfo();

        $persons = $persons->map(
            function ($key) {
                $person    = new Person($key->id);
                $key->fans = $person->numberOfFans();
                return $key;
            }
        );

        $persons = ListController::sort(Input::get('order'), Input::get('sortby'), $persons);

        $get = $this->get();

        return view(
            'list', [
                'result'     => $persons,
                'search'     => false,
                'movieList'  => false,
                'userList'   => false,
                'personList' => true,
                'get'        => $get
            ]
        );
    }

    public function user()
    {
        $users = User::getUsersInfo(['login', 'avatar']);
        $users = ListController::sort(Input::get('order'), Input::get('sortby'), $users);

        $get = $this->get();

        return view(
            'list', [
                'result'     => $users,
                'search'     => false,
                'movieList'  => false,
                'userList'   => true,
                'personList' => false,
                'get'        => $get
            ]
        );
    }
}
