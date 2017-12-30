<?php

namespace Mymovielist\NEO4J;

use NeoEloquent;

class NEO4JMovie extends NeoEloquent
{
    protected $label = 'Movie';

    protected $connection = 'neo4j';

    public $timestamps = false;

    protected $fillable = ['mid'];

    public function isGenre()
    {
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JGenre', 'IS_GENRE');
    }

}