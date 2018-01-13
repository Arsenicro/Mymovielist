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

    public static function titleExists($title)
    {
        $movies = Movie::getMoviesInfo(['title']);
        foreach ($movies as $movie) {
            if ($movie->title == $title) {
                return true;
            }
        }

        return false;
    }

    public static function create(array $data, array $genres)
    {
        $sqlMovie   = SQLMovie::create($data);
        $neo4jMovie = NEO4JMovie::create(['mid' => $sqlMovie->id]);
        $movie      = new Movie($sqlMovie->id, $sqlMovie, $neo4jMovie);
        foreach ($genres as $genre) {
            $movie->saveGenre($genre);
        }

        return $movie;
    }

    public function delete()
    {
        if (!$this->exist()) {
            return false;
        }

        $reviews = $this->getReviews();

        foreach ($reviews as $review) {
            $item = new Review($review->id);
            $item->delete();
        }

        return $this->getSqlMovie()->delete() && $this->getNeo4jMovie()->delete();
    }

    public function save(array $data)
    {
        $sqlMovie = $this->getSqlMovie();

        $sqlMovie->title       = array_key_exists('title', $data) ? $data['title'] : $sqlMovie->title;
        $sqlMovie->prod_date   = array_key_exists('prod_date', $data) ? $data['prod_date'] : $sqlMovie->prod_date;
        $sqlMovie->description = array_key_exists('description', $data) ? $data['description'] : $sqlMovie->description;
        $sqlMovie->photo       = array_key_exists('photo', $data) ? $data['photo'] : $sqlMovie->photo;

        $sqlMovie->save();
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

    public function saveGenre(Genre $genre)
    {
        $neo4jMovie = $this->getNeo4jMovie();
        $neo4jGenre = $genre->getNeo4jGenre();
        if ($this->getGenres()->contains($neo4jGenre)) {
            return false;
        }
        return $neo4jMovie->isGenre()->save($neo4jGenre) != null;
    }

    public function deleteGenre(Genre $genre)
    {
        $movie = $this->getNeo4jMovie();
        $edge  = $movie->isGenre()->edge($genre->getNeo4jGenre());

        if ($edge == null) {
            return false;
        }

        $edge->delete();

        return true;
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

        return $movie->save() != null;
    }

    public function unScore($score)
    {
        $movie          = $this->sqlMovie;
        $numberOfScores = $movie->number_of_scores;
        $oldScore       = $movie->score * $numberOfScores;

        $numberOfScores -= 1;
        if ($numberOfScores == 0) {
            $newScore = 0;
        } else {
            $newScore = ($oldScore - $score) / $numberOfScores;
        }

        $movie->number_of_scores = $numberOfScores;
        $movie->score            = $newScore;

        return $movie->save() != null;
    }

    public function getReviews()
    {
        return $this->neo4jMovie->review()->get();
    }

    public function directedBy(Person $person)
    {
        $movie       = $this->neo4jMovie;
        $neo4jPerson = $person->getNeo4jPerson();

        if ($this->getDirectors()->contains($neo4jPerson)) {
            return false;
        }

        $movie->hasDirectors()->save($neo4jPerson);
        return true;

    }

    public function deleteDirector(Person $person)
    {
        $movie = $this->getNeo4jMovie();
        $edge  = $movie->hasDirectors()->edge($person->getNeo4jPerson());

        if ($edge == null) {
            return false;
        }

        $edge->delete();

        return true;
    }

    public function wroteBy(Person $person)
    {
        $movie       = $this->neo4jMovie;
        $neo4jPerson = $person->getNeo4jPerson();

        if ($this->getWriters()->contains($neo4jPerson)) {
            return false;
        }

        $movie->hasWriters()->save($neo4jPerson);
        return true;
    }

    public function deleteWriter(Person $person)
    {
        $movie = $this->getNeo4jMovie();
        $edge  = $movie->hasWriters()->edge($person->getNeo4jPerson());

        if ($edge == null) {
            return false;
        }

        $edge->delete();

        return true;
    }

    public function newStar(Person $person, $role)
    {
        $movie       = $this->neo4jMovie;
        $neo4jPerson = $person->getNeo4jPerson();

        if ($this->getStars()->contains($neo4jPerson)) {
            return false;
        }

        $movie->hasStars()->save($neo4jPerson, ['role' => $role]);
        return true;

    }

    public function deleteStar(Person $person)
    {
        $movie = $this->getNeo4jMovie();
        $edge  = $movie->hasStars()->edge($person->getNeo4jPerson());

        if ($edge == null) {
            return false;
        }

        $edge->delete();

        return true;
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