<?php

namespace Mymovielist;

use Mockery\Exception;
use Mymovielist\NEO4J\NEO4JUser;
use Mymovielist\SQL\SQLUser;

class User
{

    public static function create(array $data)
    {
        NEO4JUser::create(['login' => $data['login']]);

        return SQLUser::create(
            [
                'login'    => $data['login'],
                'email'    => $data['email'],
                'password' => $data['password'],
                'access'   => 'u'
            ]
        );


    }

    public static function setAttribute($id,$attribute,$value)
    {
        $user = SQLUser::where('id', $id);

        switch ($attribute)
        {
            case "name":
                $user->update(['name' => $value]);
                break;
            case "surname":
                $user->update(['surname' => $value]);
                break;
            case "avatar":
                $user->update(['avatar' => $value]);
                break;
            case "birthday":
                $user->update(['birthday' => $value]);
                break;
            case "about":
                $user->update(['about' => $value]);
                break;
            case "location":
                $user->update(['location' => $value]);
                break;
            case "gender":
                $user->update(['gender' => $value]);
                break;
            default:
                throw new Exception("Wrong argument passed");
        }

    }

    public static function follow($login1,$login2)
    {
        $user1 = NEO4JUser::where('login', $login1)->first();
        $user2 = NEO4JUser::where('login', $login2)->first();

        $user1->followers()->save($user2,["since"=>"2137"]);
    }

    public static function getFollowers($login)
    {
        return NEO4JUser::where('login', $login)->first()->followers()->get();
    }

}