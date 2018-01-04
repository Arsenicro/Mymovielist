<?php

namespace Mymovielist;

use Mymovielist\NEO4J\NEO4JGenre;

class Genre
{
    private $name;
    private $neo4jGenre;

    public function __construct($name, $neo4jGenre = null)
    {
        $this->name       = $name;
        $this->neo4jGenre = $neo4jGenre ?? $this->getNeo4jGenre();
    }

    public static function create($data)
    {
        $neo4jGenre = NEO4JGenre::create($data);

        return new Genre($data['name'], $neo4jGenre);
    }

    public function getNeo4jGenre()
    {
        return $this->neo4jGenre ?? NEO4JGenre::where('name', $this->name)->first();
    }

    public function getAllMovies()
    {
        return $this->neo4jGenre->movie()->get();
    }

    public static function getAllGenres()
    {
        return NEO4JGenre::all()->pluck('name');
    }
}