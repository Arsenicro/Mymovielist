<?php

namespace Mymovielist\Http\Controllers;

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
}
