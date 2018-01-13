<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Mymovielist\Genre;
use Mymovielist\Movie;
use Mymovielist\Person;

class AddingController extends Controller
{
    public function index()
    {
        return view('adding');
    }

    public function addMovie()
    {
        $title = Input::get('movieTitle');
        if ($title == "" || Movie::titleExists($title)) {
            return redirect('/adding')->with('error', "Something went wrong!");
        }

        $movie = Movie::create(['title' => $title], []);
        return redirect()->route('movie', [$movie->getMovieInfo()->id])->with('message', 'Created!');
    }

    public function addGenre()
    {
        $genreName = Input::get('genreName');
        $genre     = new Genre($genreName);
        if ($genreName == "" || $genre->exist()) {
            return redirect()->route('adding')->with('error', "Something went wrong!");
        }

        $genre = Genre::create(['name' => $genreName]);
        return redirect()->route('adding')->with('message', $genre->getNeo4jGenre()->name . ' created!');
    }

    public function deleteGenre()
    {
        $genreName = Input::get('genreName');
        $genre     = new Genre($genreName);
        if ($genreName == "" || !$genre->delete()) {
            return redirect()->route('adding')->with('error', "Something went wrong!");
        }

        return redirect()->route('adding')->with('message', "Deleted!");
    }

    public function addPerson()
    {
        $personName    = Input::get('personName');
        $personSurname = Input::get('personSurname');

        if (Person::nameAndSurnameExist($personName, $personSurname)) {
            return redirect()->route('adding')->with('error', "Something went wrong!");
        }

        $person = Person::create(['name' => $personName, 'surname' => $personSurname]);

        return redirect()->route('person', [$person->getPersonInfo()->id])->with('message', "Added!");
    }
}
