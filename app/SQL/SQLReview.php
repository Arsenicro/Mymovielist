<?php

namespace Mymovielist\SQL;

use Illuminate\Database\Eloquent\Model;

class SQLReview extends Model
{
    protected $table = 'reviews';
    protected $connection = 'mysql';

    protected $fillable = ['text'];
}