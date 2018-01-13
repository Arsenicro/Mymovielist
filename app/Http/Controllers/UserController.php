<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rules\In;
use Mymovielist\EditHistory;
use Mymovielist\Movie;
use Mymovielist\NEO4J\NEO4JMovie;
use Mymovielist\User;

class UserController extends Controller
{
    public function user($login)
    {
        $user     = new User($login);
        $info     = $user->getUserInfo();
        $followed = false;
        $me       = false;

        if (!$user->exist()) {
            return abort(404);
        }
        if (Auth::user() != null) {
            $authUser = new User(Auth::user()->login);

            $me = Auth::user()->login == $login;
            if (!$me) {
                $followed = $authUser->following($user);
            }
        }


        return view(
            'user', [
                'info'     => $info,
                'me'       => $me,
                'followed' => $followed
            ]
        );
    }

    public function edit($login)
    {
        $user = new User($login);
        $info = $user->getUserInfo();

        if (!$user->exist()) {
            return abort(404);
        }

        return view(
            'edituser',
            [
                'info'    => $info,
                'isAdmin' => Auth::user()->access == "a"
            ]
        );
    }

    public function saveAccess($login)
    {
        $user   = new User($login);
        $access = Input::get('access');

        if (!$user->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'access', $user->getUserInfo()->access);

        if ($access == 'Admin') {
            $user->setAttribute(['access' => 'a']);
        } elseif ($access == 'Moderator') {
            $user->setAttribute(['access' => 'm']);
        } else {
            $user->setAttribute(['access' => 'u']);
        }

        return redirect()->route('editUser', [$login])->with('message', 'Saved');
    }

    public function saveName($login)
    {
        $user = new User($login);
        $name = Input::get('name');
        if (!$user->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'name', $user->getUserInfo()->name);
        $user->setAttribute(['name' => $name]);

        return redirect()->route('editUser', [$login])->with('message', 'Saved');
    }

    public function saveSurname($login)
    {
        $user    = new User($login);
        $surname = Input::get('surname');
        if (!$user->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'surname', $user->getUserInfo()->surname);
        $user->setAttribute(['surname' => $surname]);

        return redirect()->route('editUser', [$login])->with('message', 'Saved');
    }

    public function saveAvatar($login)
    {
        $user   = new User($login);
        $avatar = Input::get('avatar');
        if (!$user->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'avatar', $user->getUserInfo()->avatar);
        $user->setAttribute(['avatar' => $avatar]);

        return redirect()->route('editUser', [$login])->with('message', 'Saved');
    }

    public function saveBirthday($login)
    {
        $user     = new User($login);
        $birthday = Input::get('birthday');
        if (!$user->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        if (date('Y-m-d', strtotime($birthday)) == $birthday) {

            $history = new EditHistory('user');
            $history->saveEdit($login, 'birthday', $user->getUserInfo()->birthday);
            $user->setAttribute(['birthday' => $birthday]);

            return redirect()->route('editUser', [$login])->with('message', 'Saved');
        }

        return redirect()->back()->with('error', 'Invalid date!');

    }

    public function saveAbout($login)
    {
        $user  = new User($login);
        $about = Input::get('about');
        if (!$user->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'about', $user->getUserInfo()->about);
        $user->setAttribute(['about' => $about]);

        return redirect()->route('editUser', [$login])->with('message', 'Saved');
    }

    public function saveLocation($login)
    {
        $user     = new User($login);
        $location = Input::get('location');
        if (!$user->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'location', $user->getUserInfo()->location);
        $user->setAttribute(['location' => $location]);

        return redirect()->route('editUser', [$login])->with('message', 'Saved');
    }

    public function saveGender($login)
    {
        $user   = new User($login);
        $gender = strtolower(Input::get('gender'));
        if (!$user->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'gender', $user->getUserInfo()->gender);
        $user->setAttribute(['gender' => $gender]);

        return redirect()->route('editUser', [$login])->with('message', 'Saved');
    }

    public function followOrNot($login)
    {
        $followUser = new User($login);
        $authUser   = new User(Auth::user()->login);
        if ($authUser->getUserInfo()->login != $followUser->getUserInfo()->login) {
            if ($authUser->following($followUser)) {
                if ($authUser->unFollow($followUser)) {
                    return redirect()->back()->with('message', 'Unfollowed!');
                }
            } else {
                $authUser->follow($followUser);
                return redirect()->back()->with('message', 'Followed!');
            }
        }

        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public function deleteUser($login)
    {
        $user = new User($login);
        if ($user->delete()) {
            return redirect()->route('userList')->with('message', 'Deleted!');
        }
        return redirect()->back()->with('error', 'Something went wrong!');
    }
}
