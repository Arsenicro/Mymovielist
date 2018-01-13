<?php

namespace Mymovielist\NEO4J;

use Everyman\Neo4j\Cypher\Query;
use Illuminate\Support\Facades\DB;
use Mymovielist\Movie;
use NeoEloquent;

class NEO4JUser extends NeoEloquent
{
    protected $label = 'User';

    protected $connection = 'neo4j';

    public $timestamps = false;

    protected $fillable = ['login'];

    public function followed()
    {
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JUser', 'FOLLOWED_BY');
    }

    public function followers()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JUser', 'FOLLOWED_BY');
    }

    public function wroteReview()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JReview', 'WROTE_REVIEW');
    }

    public function isFan()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JPerson', 'IS_FAN');
    }

    public function like()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JMovie', 'LIKE');
    }

    public function doesNotLike()
    {
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JMovie', 'DOES_NOT_LIKE');
    }

    public function score()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JMovie', 'SCORED');
    }

    public static function myQuery($login)
    {
        $client      = DB::connection('neo4j')->getClient();
        $queryString = "MATCH (u1)-[:FOLLOWED_BY]->(u2)-[:IS_FAN]->(p)-[:STAR]->(m) WHERE u1.login=\"{$login}\" RETURN (m)";
        $query       = new Query($client, $queryString, array('userId' => $login));
        $result      = $query->getResultSet();
        $midArray    = [];
        foreach ($result as $row) {
            array_push($midArray, $row['m']->getProperties()['mid']);
        }

        $collection = collect($midArray)->map(
            function ($key) {
                $movie = new Movie($key);
                return $movie;
            }
        );

        return $collection;
    }
}