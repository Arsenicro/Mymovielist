<?php

namespace Mymovielist\NEO4J;

use NeoEloquent;

class NEO4JGenre extends NeoEloquent
{
    protected $label = 'Genre';

    protected $connection = 'neo4j';

    public $timestamps = false;

    protected $fillable = ['name'];

    public function movie()
    {
        return $this->belongsToMany('Mymovielist\NEO4J\NEO4JMovie', 'IS_GENRE');
    }

}