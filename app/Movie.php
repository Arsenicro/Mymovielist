<?php

namespace Mymovielist;

use Mymovielist\NEO4J\NEO4JMovie;
use Mymovielist\SQL\SQLMovie;

class Movie
{
    public static function create(array $data, $genres)
    {
        $sqlMovie   = SQLMovie::create($data);
        $neo4jMovie = NEO4JMovie::create(['mid' => $sqlMovie->id]);
        foreach ($genres as $genre) {
            $neo4jMovie->isGenre()->save(Genre::getNeo4jGenre($genre));
        }

        return $sqlMovie->id;
    }

    public static function getNeo4jMovie($mid)
    {
        return NEO4JMovie::where('mid', $mid)->first();
    }

    public static function getGenres($mid)
    {
        return NEO4JMovie::where('mid',$mid)->first()->isGenre()->get();
    }
}