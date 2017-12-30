<?php

namespace Mymovielist;


use Mymovielist\NEO4J\NEO4JPerson;
use Mymovielist\SQL\SQLPerson;

class Person
{
    public static function create(array $data)
    {
        $person = SQLPerson::create($data);
        NEO4JPerson::create(['pid' => $person->id]);

        return $person->id;
    }
}