<?php

namespace Mymovielist\Http\Controllers\Auth;

use Mymovielist\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mymovielist\User;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';


    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        return Validator::make(
            $data, [
            'name'     => 'required|string|max:255|unique:mysql.users',
            'email'    => 'required|string|email|max:255|unique:mysql.users',
            'password' => 'required|string|min:6|confirmed',
        ]
        );
    }

    protected function create(array $data)
    {
        if (User::getUsersInfo()->count() == 0) {
            $access = 'a';
        } else {
            $access = 'u';
        }
        $user = User::create(
            [
                'login'    => $data['name'],
                'email'    => $data['email'],
                'password' => bcrypt($data['password']),
                'access'   => $access
            ]
        );
        return $user->getSqlUser();
    }
}
