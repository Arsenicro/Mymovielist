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

        if (Auth::user() != null) {
            $user  = new User(Auth::user()->login);
            $liked = $person->likedBy($user);
        }


        return view(
            'person', [
                'info'     => $personInfo,
                'played'   => $played,
                'directed' => $directed,
                'wrote'    => $wrote,
                'liked'    => $liked ?? false
            ]
        );

    }

    public function list()
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
        $get     = ListController::get();

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

    public function add()
    {
        $personName    = Input::get('personName');
        $personSurname = Input::get('personSurname');

        if (Person::nameAndSurnameExist($personName, $personSurname)) {
            return redirect()->back()->with('error', "Something went wrong!");
        }

        $person = Person::create(['name' => $personName, 'surname' => $personSurname]);

        return redirect()->route('person', [$person->getPersonInfo()->id])->with('message', "Added!");
    }

    public function edit($pid)
    {
        $person = new Person($pid);

        if (!$person->exist()) {
            abort(404);
        }

        $personInfo = $person->getPersonInfo();

        return view(
            'editperson', [
                'info' => $personInfo
            ]
        );

    }

    public function save($pid)
    {
        $name      = true;
        $surname   = true;
        $birthday  = true;
        $biography = true;
        $image     = true;

        if (Input::get('name') !== Input::get('oldname')) {
            $name = $name && $this->saveName($pid, Input::get('name'));
        }
        if (Input::get('surname') !== Input::get('oldsurname')) {
            $surname = $surname && $this->saveSurname($pid, Input::get('surname'));
        }
        if (Input::get('birthday') !== Input::get('oldbirthday')) {
            $birthday = $birthday && $this->saveBirthday($pid, Input::get('birthday'));
        }
        if (Input::get('biography') !== Input::get('oldbiography')) {
            $biography = $biography && $this->saveBiography($pid, Input::get('biography'));
        }
        if (Input::get('image') !== Input::get('oldimage')) {
            $image = $image && $this->saveImage($pid, Input::get('image'));
        }
        if ($name && $surname && $birthday && $biography && $image) {
            return redirect()->back()->with('message', 'Saved');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function saveImage($pid, $photo)
    {
        $person = new Person($pid);

        if (!$person->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('person');
        $history->saveEdit($pid, 'photo', $person->getPersonInfo()->photo);
        $person->setAttribute(['photo' => $photo]);

        return redirect()->route('editPerson', [$pid])->with('message', 'Saved');
    }

    public function saveBirthday($pid, $birthday)
    {
        $person = new Person($pid);

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

    public function saveName($pid, $name)
    {
        $person = new Person($pid);
        if (!$person->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('person');
        $history->saveEdit($pid, 'name', $person->getPersonInfo()->name);
        $person->setAttribute(['name' => $name]);

        return redirect()->route('editPerson', [$pid])->with('message', 'Saved');
    }

    public function saveSurname($pid, $surname)
    {
        $person = new Person($pid);

        if (!$person->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('person');
        $history->saveEdit($pid, 'surname', $person->getPersonInfo()->surname);
        $person->setAttribute(['surname' => $surname]);

        return redirect()->route('editPerson', [$pid])->with('message', 'Saved');
    }

    public function saveBiography($pid, $biography)
    {
        $person = new Person($pid);

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
