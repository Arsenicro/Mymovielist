<?php

namespace Mymovielist;

use Mymovielist\NEO4J\NEO4JReview;
use Mymovielist\SQL\SQLReview;

class Review
{
    public static function create(array $data, $mid, $login)
    {
        $sqlReview = SQLReview::create(
            [
                'text'  => $data['text'],
                'score' => $data['score']
            ]
        );

        $neo4jReview = NEO4JReview::create(['rid' => $sqlReview->id]);

        $user  = User::getNeo4jUser($login);
        $movie = Movie::getNeo4jMovie($mid);

        $neo4jReview->wroteBy()->associate($user)->save();
        $neo4jReview->movie()->associate($movie)->save();

        return $sqlReview->id;
    }

    public static function getNeo4jReview($rid)
    {
        return NEO4JReview::where('rid', $rid)->first();
    }

    public static function getAuthor($rid)
    {
        return Review::getNeo4jReview($rid)->wroteBy()->get()->first();
    }

    public static function getMovie($rid)
    {
        return Review::getNeo4jReview($rid)->movie()->get()->first();
    }


}