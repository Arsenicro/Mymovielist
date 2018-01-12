<?php

namespace Mymovielist\NEO4J;

use NeoEloquent;

class NEO4JUser extends NeoEloquent
{
    protected $label = 'User';

    protected $connection = 'neo4j';

    public $timestamps = false;

    protected $fillable = ['login'];

    public function followers()
    {
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JUser', 'FOLLOWED_BY');
    }

    public function followed()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JUser', 'FOLLOWED_BY');
    }

    public function wroteReview()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JReview', 'REVIEWED');
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
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JMovie', 'SCORED');
    }
}