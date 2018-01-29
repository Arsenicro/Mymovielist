<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Mymovielist\Genre;
use Mymovielist\Movie;
use Mymovielist\Person;

class GenreController extends Controller
{
    public function add()
    {
        $genreName = Input::get('genreName');
        $genre     = new Genre($genreName);
        if ($genreName == "" || $genre->exist()) {
            return redirect()->route('adding')->with('error', "Something went wrong!");
        }

        $genre = Genre::create(['name' => $genreName]);
        return redirect()->route('adding')->with('message', $genre->getNeo4jGenre()->name . ' created!');
    }

    public function delete()
    {
        $genreName = Input::get('genreName');
        $genre     = new Genre($genreName);
        if ($genreName == "" || !$genre->delete()) {
            return redirect()->route('adding')->with('error', "Something went wrong!");
        }

        return redirect()->route('adding')->with('message', "Deleted!");
    }
}
