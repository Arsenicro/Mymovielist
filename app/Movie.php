<?php

namespace Mymovielist;

use Mymovielist\NEO4J\NEO4JMovie;
use Mymovielist\SQL\SQLMovie;

class Movie
{

    private $mid;
    private $neo4jMovie = null;
    private $sqlMovie = null;

    public function __construct($mid, $sqlMovie = null, $neo4jMovie = null)
    {
        $this->mid        = intval($mid);
        $this->sqlMovie   = $sqlMovie ?? $this->getSqlMovie();
        $this->neo4jMovie = $neo4jMovie ?? $this->getNeo4jMovie();
    }

    public function exist()
    {
        if ($this->sqlMovie == null || $this->neo4jMovie == null) {
            return false;
        }

        return true;
    }

    public static function create(array $data, array $genres)
    {
        $sqlMovie   = SQLMovie::create($data);
        $neo4jMovie = NEO4JMovie::create(['mid' => $sqlMovie->id]);
        foreach ($genres as $genre) {
            $neo4jMovie->isGenre()->save(Genre::getNeo4jGenre($genre));
        }

        return new Movie($sqlMovie->id, $sqlMovie, $neo4jMovie);
    }

    public function getNeo4jMovie()
    {
        return $this->neo4jMovie ?? NEO4JMovie::where('mid', $this->mid)->first();
    }

    public function getSqlMovie()
    {
        return $this->sqlMovie ?? SQLMovie::where('id', $this->mid)->first();
    }

    public function getMovieInfo()
    {
        return $this->sqlMovie;
    }

    public static function getMoviesInfo($columns = null)
    {
        if ($columns != null) {
            return SQLMovie::all($columns);
        }
        return SQLMovie::all();
    }

    public function getGenres()
    {
        return $this->neo4jMovie->isGenre()->get();
    }

    public function getLikers()
    {
        return $this->neo4jMovie->isLiked()->get();
    }

    public function getNotLikers()
    {
        return $this->neo4jMovie->isNotLiked()->get();
    }

    public function newScore($score)
    {
        $movie          = $this->sqlMovie;
        $numberOfScores = $movie->number_of_scores;
        $oldScore       = $movie->score * $numberOfScores;

        $numberOfScores += 1;
        $newScore       = ($oldScore + $score) / $numberOfScores;

        $movie->number_of_scores = $numberOfScores;
        $movie->score            = $newScore;
        $movie->save();
    }

    public function getReviews()
    {
        return $this->neo4jMovie->review()->get();
    }

    public function directedBy(Person $person)
    {
        $movie       = $this->neo4jMovie;
        $neo4jPerson = $person->getNeo4jPerson();

        $movie->hasDirectors()->save($neo4jPerson);
    }

    public function wroteBy(Person $person)
    {
        $movie       = $this->neo4jMovie;
        $neo4jPerson = $person->getNeo4jPerson();

        $movie->hasWriters()->save($neo4jPerson);
    }

    public function newStar(Person $person, $role)
    {
        $movie       = $this->neo4jMovie;
        $neo4jPerson = $person->getNeo4jPerson();

        $movie->hasStars()->save($neo4jPerson, ['role' => $role]);
    }

    public function getStars()
    {
        return $this->neo4jMovie->hasStars()->get();
    }

    public function getDirectors()
    {
        return $this->neo4jMovie->hasDirectors()->get();
    }

    public function getWriters()
    {
        return $this->neo4jMovie->hasWriters()->get();
    }
}