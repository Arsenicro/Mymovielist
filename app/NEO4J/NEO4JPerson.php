<?php

namespace Mymovielist\NEO4J;

use NeoEloquent;

class NEO4JPerson extends NeoEloquent
{
    protected $label = 'Person';

    protected $connection = 'neo4j';

    public $timestamps = false;

    protected $fillable = ['pid'];

    public function hasFan()
    {
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JUser', 'FAN');
    }

    public function isDirector()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JMovie', 'DIRECTED');
    }

    public function isWriter()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JMovie', 'WROTE');
    }

    public function isStar()
    {
        return $this->hasMany('Mymovielist\NEO4J\NEO4JMovie', 'STAR');
    }

}