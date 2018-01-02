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

    public static function getNeo4jPerson($pid)
    {
        return NEO4JPerson::where('pid', $pid)->first();
    }

    public static function getFans($pid)
    {
        return Person::getNeo4jPerson($pid)->hasFan()->get();
    }

    public static function getWrote($pid)
    {
        return Person::getNeo4jPerson($pid)->isWriter()->get();
    }

    public static function getDirected($pid)
    {
        return Person::getNeo4jPerson($pid)->isDirector()->get();
    }

    public static function getPlayed($pid)
    {
        return Person::getNeo4jPerson($pid)->isStar()->get();
    }

    public static function getRole($pid, $mid)
    {
        $person = Person::getNeo4jPerson($pid);
        $movie  = Movie::getNeo4jMovie($mid);

        return $person->isStar()->edge($movie)->role;
    }
}