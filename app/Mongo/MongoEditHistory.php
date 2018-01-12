<?php

namespace Mymovielist\Mongo;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class MongoEditHistory extends Eloquent
{
    protected $connection = "mongodb";
}