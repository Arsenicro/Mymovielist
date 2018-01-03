<?php

namespace Mymovielist\NEO4J;

use NeoEloquent;

class NEO4JReview extends NeoEloquent
{
    protected $label = 'Review';

    protected $connection = 'neo4j';

    public $timestamps = false;

    protected $fillable = ['rid'];

    public function movie()
    {
        return $this->belongsTo('Mymovielist\NEO4J\NEO4JMovie', 'HAS_REVIEW');
    }

    public function wroteBy()
    {
        return $this->belongsTo('Mymovielist\NEO4J\NEO4JUser', 'WROTE');
    }
}