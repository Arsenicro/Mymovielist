<?php

namespace Mymovielist;

use Mymovielist\NEO4J\NEO4JUser;
use Mymovielist\SQL\SQLUser;

class User
{

    public static function create(array $data)
    {
        NEO4JUser::create(['login' => $data['login']]);

        return SQLUser::create([
            'login' => $data['login'],
            'email' => $data['email'],
            'password' => $data['password'],
            'access' => 'u'
        ]);


    }

}