<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mymovielist\Movie;

class ListController extends Controller
{
    public function movie()
    {
        $movies = Movie::getMoviesInfo(['id','title','score','photo','prod_date']);
        if(Input::get('order') == 'asc')
            $movies = $movies->sortBy(Input::get('sortby'));
        elseif (Input::get('order') == 'desc')
            $movies = $movies->sortByDesc(Input::get('sortby'));

        return view('movielist', ['movies' => $movies]);
    }
}
