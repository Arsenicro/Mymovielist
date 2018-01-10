<?php

namespace Mymovielist;

use Mymovielist\NEO4J\NEO4JReview;
use Mymovielist\SQL\SQLReview;

class Review
{
    private $rid;
    private $sqlReview;
    private $neo4jReview;

    public function __construct($rid, $sqlReview = null, $neo4jReview = null)
    {
        $this->rid         = intval($rid);
        $this->sqlReview   = $sqlReview ?? $this->getSqlReview();
        $this->neo4jReview = $neo4jReview ?? $this->getNeo4jReview();
    }

    public function exist()
    {
        if ($this->sqlReview == null || $this->neo4jReview == null) {
            return false;
        }

        return true;
    }

    public static function create(array $data, $mid, $login)
    {
        $sqlReview   = SQLReview::create($data);
        $neo4jReview = NEO4JReview::create(['rid' => $sqlReview->id]);
        $user        = new User($login);
        $movie       = new Movie($mid);

        $neo4jUser  = $user->getNeo4jUser();
        $neo4jMovie = $movie->getNeo4jMovie();

        $neo4jReview->wroteBy()->associate($neo4jUser)->save();
        $neo4jReview->movie()->associate($neo4jMovie)->save();

        return new Review($sqlReview->id, $sqlReview, $neo4jReview);
    }

    public function getSqlReview()
    {
        return $this->sqlReview ?? SQLReview::where('id', $this->rid)->first();
    }

    public function getNeo4jReview()
    {
        return $this->neo4jReview ?? NEO4JReview::where('rid', $this->rid)->first();
    }

    public function getReviewInfo()
    {
        return $this->sqlReview;
    }

    public static function getAllReviewsInfo($columns = null)
    {
        if ($columns != null) {
            return SQLReview::all($columns);
        }
        return SQLReview::all();
    }

    public function getAuthor()
    {
        return $this->neo4jReview->wroteBy()->get()->first();
    }

    public function getMovie()
    {
        return $this->neo4jReview->movie()->get()->first();
    }


}