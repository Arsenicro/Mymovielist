<?php

namespace Mymovielist\Http\Controllers;

use Illuminate\Http\Request;
use Mymovielist\User;

class UserController extends Controller
{
    public function user($login)
    {
        $user = new User($login);
        $info = $user->getUserInfo();

        if(!$user->exist())
            return abort(404);

        return view('user', ['info' => $info]);
    }
}
