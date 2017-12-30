<?php

namespace Mymovielist\NEO4J;

use NeoEloquent;

class NEO4JPerson extends NeoEloquent
{
    protected $label = 'Person';

    protected $connection = 'neo4j';

    public $timestamps = false;

    protected $fillable = ['pid'];

}