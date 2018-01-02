<?php

namespace Mymovielist;

use Mymovielist\NEO4J\NEO4JMovie;
use Mymovielist\SQL\SQLMovie;

class Movie
{
    public static function create(array $data, array $genres)
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

    public static function getAvgScore($mid)
    {
        $movie          = Movie::getNeo4jMovie($mid);
        $numberOfScores = $movie->number_of_scores;
        $realScore      = $movie->score;

        return $numberOfScores / $realScore;
    }

    public static function getGenres($mid)
    {
        return Movie::getNeo4jMovie($mid)->isGenre()->get();
    }

    public static function getLikers($mid)
    {
        return Movie::getNeo4jMovie($mid)->isLiked()->get();
    }

    public static function getNotLikers($mid)
    {
        return Movie::getNeo4jMovie($mid)->isNotLiked()->get();
    }

    public static function newScore($mid, $score)
    {
        $movie          = Movie::getNeo4jMovie($mid);
        $numberOfScores = $movie->number_of_scores;
        $oldScore       = $movie->score;

        $numberOfScores += 1;
        $newScore       = $oldScore + $score;

        $movie->number_of_scores = $numberOfScores;
        $movie->score            = $newScore;
        $movie->save();
    }

    public static function getUserScore($login, $mid)
    {
        $user  = User::getNeo4jUser($login);
        $movie = Movie::getNeo4jMovie($mid);

        return $user->score()->edge($movie)->score;
    }

    public static function getReviews($mid)
    {
        return Movie::getNeo4jMovie($mid)->review()->get();
    }

    public static function directedBy($mid,$pid)
    {
        $movie = Movie::getNeo4jMovie($mid);
        $person = Person::getNeo4jPerson($pid);

        $movie->hasDirectors()->save($person);
    }

    public static function wroteBy($mid,$pid)
    {
        $movie = Movie::getNeo4jMovie($mid);
        $person = Person::getNeo4jPerson($pid);

        $movie->hasWriters()->save($person);
    }

    public static function newStar($mid,$pid,$role)
    {
        $movie = Movie::getNeo4jMovie($mid);
        $person = Person::getNeo4jPerson($pid);

        $movie->hasStars()->save($person,['role' => $role]);
    }

    public static function getStars($mid)
    {
        return Movie::getNeo4jMovie($mid)->hasStars()->get();
    }

    public static function getDirectors($mid)
    {
        return Movie::getNeo4jMovie($mid)->hasDirectors()->get();
    }

    public static function getWriters($mid)
    {
        return Movie::getNeo4jMovie($mid)->hasWriters()->get();
    }
}