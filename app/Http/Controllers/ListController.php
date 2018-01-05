<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mymovielist\Movie;

class ListController extends Controller
{
    public function index()
    {
        $movies = Movie::getMoviesInfo(['title','score','photo','prod_date']);
        $movies = $movies->map(function ($item)
        {
           $item->photo = $item->photo ?? 'https://websoul.pl/blog/wp-content/uploads/2013/06/question-mark1.jpg';
            return $item;
        });

        if(Input::get('order') == 'asc')
            $movies = $movies->sortBy(Input::get('sortby'));
        elseif (Input::get('order') == 'desc')
            $movies = $movies->sortByDesc(Input::get('sortby'));

        return view('list', ['movies' => $movies]);
    }
}
