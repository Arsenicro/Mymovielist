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
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JUser', 'follows');
    }
}