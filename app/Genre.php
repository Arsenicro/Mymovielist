<?php

namespace Mymovielist;

use Mymovielist\NEO4J\NEO4JGenre;

class Genre
{
    public static function create($data)
    {
        NEO4JGenre::create($data);

        return $data['name'];
    }

    public static function getNeo4jGenre($name)
    {
        return NEO4JGenre::where('name', $name)->first();
    }

    public static function getAllMovies($name)
    {
        return Genre::getNeo4jGenre($name)->movie()->get();
    }
}