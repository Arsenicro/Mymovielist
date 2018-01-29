<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\Rules\In;
use Mymovielist\EditHistory;
use Mymovielist\Movie;
use Mymovielist\Review;
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

        $reviews = $user->getReviews();

        $reviews = $reviews->map(
            function ($key) use ($user) {
                $review = new Review($key->rid);
                $movie  = new Movie($review->getMovie()->mid);
                $score  = $user->scored($movie) ? $user->getUserScore($movie) : "N/A";

                return ['info' => $review->getReviewInfo(), 'movie' => $movie->getMovieInfo(), 'score' => $score];
            }
        );


        return view(
            'user', [
                'info'     => $info,
                'me'       => $me,
                'followed' => $followed,
                'reviews'  => $reviews
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

    public function save($login)
    {
        $name     = true;
        $surname  = true;
        $birthday = true;
        $about    = true;
        $avatar   = true;
        $access   = true;
        $gender   = true;

        $user = new User(Auth::user()->login);

        if (Input::get('name') !== Input::get('oldname')) {
            $name = $name && $this->saveName($login, Input::get('name'));
        }
        if (Input::get('surname') !== Input::get('oldsurname')) {
            $surname = $surname && $this->saveSurname($login, Input::get('surname'));
        }
        if (Input::get('birthday') !== Input::get('oldbirthday')) {
            $birthday = $birthday && $this->saveBirthday($login, Input::get('birthday'));
        }
        if (Input::get('about') !== Input::get('oldabout')) {
            $about = $about && $this->saveAbout($login, Input::get('about'));
        }
        if (Input::get('avatar') !== Input::get('oldavatar')) {
            $avatar = $avatar && $this->saveAvatar($login, Input::get('avatar'));
        }
        if (Input::get('gender') !== Input::get('oldgender')) {
            $gender = $gender && $this->saveGender($login, Input::get('gender'));
        }
        if ($user->isAdmin() && Input::get('access') !== Input::get('oldaccess')) {
            $access = $access && $this->saveAccess($login, Input::get('access'));
        }
        if ($name && $surname && $birthday && $about && $avatar && $access && $gender) {
            return redirect()->back()->with('message', 'Saved');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function saveAccess($login, $access)
    {
        $user = new User($login);

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

    public function saveName($login, $name)
    {
        $user = new User($login);

        if (!$user->exist()) {
            return false;
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'name', $user->getUserInfo()->name);
        $user->setAttribute(['name' => $name]);

        return true;
    }

    public function saveSurname($login, $surname)
    {
        $user = new User($login);
        if (!$user->exist()) {
            return false;
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'surname', $user->getUserInfo()->surname);
        $user->setAttribute(['surname' => $surname]);

        return true;
    }

    public function saveAvatar($login, $avatar)
    {
        $user = new User($login);
        if (!$user->exist()) {
            return false;
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'avatar', $user->getUserInfo()->avatar);
        $user->setAttribute(['avatar' => $avatar]);

        return true;
    }

    public function saveBirthday($login, $birthday)
    {
        $user = new User($login);
        if (!$user->exist()) {
            return redirect()->back()->with('error', 'Something went wrong!');
        }

        if (date('Y-m-d', strtotime($birthday)) == $birthday) {

            $history = new EditHistory('user');
            $history->saveEdit($login, 'birthday', $user->getUserInfo()->birthday);
            $user->setAttribute(['birthday' => $birthday]);

            return true;
        }

        return false;

    }

    public function saveAbout($login, $about)
    {
        $user = new User($login);
        if (!$user->exist()) {
            return false;
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'about', $user->getUserInfo()->about);
        $user->setAttribute(['about' => $about]);

        return true;
    }

    public function saveLocation($login, $location)
    {
        $user = new User($login);
        if (!$user->exist()) {
            return false;
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'location', $user->getUserInfo()->location);
        $user->setAttribute(['location' => $location]);

        return true;
    }

    public function saveGender($login, $gender)
    {
        $user = new User($login);
        if (!$user->exist()) {
            return false;
        }

        $history = new EditHistory('user');
        $history->saveEdit($login, 'gender', $user->getUserInfo()->gender);
        $user->setAttribute(['gender' => $gender]);

        return true;
    }

    public function followOrNot($login)
    {
        $followUser = new User($login);
        $authUser   = new User(Auth::user()->login);
        if ($authUser->getUserInfo()->login != $followUser->getUserInfo()->login) {
            if ($authUser->following($followUser)) {
                $authUser->unFollow($followUser);
                return redirect()->back()->with('message', 'Unfollowed!');
            } else {
                $authUser->follow($followUser);
                return redirect()->back()->with('message', 'Followed!');
            }
        }

        return redirect()->back()->with('error', 'Something went wrong!');
    }

    public
    function deleteUser(
        $login
    ) {
        $user = new User($login);
        if ($user->delete()) {
            return redirect()->route('userList')->with('message', 'Deleted!');
        }
        return redirect()->back()->with('error', 'Something went wrong!');
    }
}
