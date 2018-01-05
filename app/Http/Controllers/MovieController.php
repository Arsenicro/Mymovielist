<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index($title)
    {
        return $title;
    }
}
