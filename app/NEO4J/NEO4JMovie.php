<?php

namespace Mymovielist\NEO4J;

use NeoEloquent;

class NEO4JMovie extends NeoEloquent
{
    protected $label = 'Movie';

    protected $connection = 'neo4j';

    public $timestamps = false;

    protected $fillable = ['mid'];

    public function review()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JReview', 'HAS_REVIEW');
    }

    public function isGenre()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JGenre', 'IS_GENRE');
    }

    public function isLiked()
    {
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JUser', 'LIKE');
    }

    public function isNotLiked()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JUser', 'DOES_NOT_LIKE');
    }

    public function scores()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JUser', 'SCORED');
    }

    public function hasDirectors()
    {
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JPerson', 'DIRECTED');
    }

    public function hasWriters()
    {
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JPerson', 'WROTE');
    }

    public function hasStars()
    {
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JPerson', 'STAR');
    }

}