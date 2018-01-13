<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Mymovielist\EditHistory;
use Mymovielist\Movie;
use Mymovielist\Person;
use Mymovielist\User;

class PersonController extends Controller
{
    public function person($pid)
    {
        $person = new Person($pid);

        if (!$person->exist()) {
            abort(404);
        }

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

        $user = new User(Auth::user()->login);


        return view(
            'person', [
                'info'     => $personInfo,
                'played'   => $played,
                'directed' => $directed,
                'wrote'    => $wrote,
                'liked'    => $person->likedBy($user)
            ]
        );

    }

    public function edit($pid)
    {
        $person = new Person($pid);

        if (!$person->exist()) {
            abort(404);
        }

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
            'editperson', [
                'info'     => $personInfo,
                'played'   => $played,
                'directed' => $directed,
                'wrote'    => $wrote
            ]
        );

    }

    public function saveImage($pid)
    {
        $person = new Person($pid);
        $photo  = Input::get('photo');

        if (!$person->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('person');
        $history->saveEdit($pid, 'photo', $person->getPersonInfo()->photo);
        $person->setAttribute(['photo' => $photo]);

        return redirect()->route('editPerson', [$pid])->with('message', 'Saved');
    }

    public function saveBirthday($pid)
    {
        $person   = new Person($pid);
        $birthday = Input::get('birthday');

        if (!$person->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        if (date('Y-m-d', strtotime($birthday)) == $birthday) {

            $history = new EditHistory('person');
            $history->saveEdit($pid, 'birthday', $person->getPersonInfo()->birthday);
            $person->setAttribute(['birthday' => $birthday]);

            return redirect()->route('editPerson', [$pid])->with('message', 'Saved');
        }

        return redirect()->back()->with('error', 'Invalid date!');

    }

    public function saveName($pid)
    {
        $person = new Person($pid);
        $name   = Input::get('name');

        if (!$person->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('person');
        $history->saveEdit($pid, 'name', $person->getPersonInfo()->name);
        $person->setAttribute(['name' => $name]);

        return redirect()->route('editPerson', [$pid])->with('message', 'Saved');
    }

    public function saveSurname($pid)
    {
        $person  = new Person($pid);
        $surname = Input::get('surname');

        if (!$person->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('person');
        $history->saveEdit($pid, 'surname', $person->getPersonInfo()->surname);
        $person->setAttribute(['surname' => $surname]);

        return redirect()->route('editPerson', [$pid])->with('message', 'Saved');
    }

    public function saveBiography($pid)
    {
        $person    = new Person($pid);
        $biography = Input::get('biography');

        if (!$person->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('person');
        $history->saveEdit($pid, 'biography', $person->getPersonInfo()->biography);
        $person->setAttribute(['biography' => $biography]);

        return redirect()->route('editPerson', [$pid])->with('message', 'Saved');
    }

    public function deletePerson($pid)
    {
        $person = new Person($pid);
        if ($person->delete()) {
            return redirect()->route('personList')->with('message', 'Deleted!');
        }
        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function likeOrNot($pid)
    {
        $person = new Person($pid);
        $user   = new User(Auth::user()->login);

        if ($person->likedBy($user)) {
            if ($user->unMakeFan($person)) {
                return redirect()->back()->with('message', 'Unliked!');
            }
        } else {
            $user->makeFan($person);
            return redirect()->back()->with('message', 'Liked!');
        }

        return redirect()->back()->with('error', 'Something went wrong!');
    }
}
