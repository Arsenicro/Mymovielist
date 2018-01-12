<?php

namespace Mymovielist\Mongo;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MongoSearchStats extends Eloquent
{
    protected $connection = "mongodb";
}