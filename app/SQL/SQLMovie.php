<?php

namespace Mymovielist\SQL;

use Illuminate\Database\Eloquent\Model;

class SQLMovie extends Model
{
    protected $table = 'movies';
    protected $connection = 'mysql';

    protected $fillable = ['title', 'prod_date', 'description', 'score', 'number_of_scores', 'photo'];
}