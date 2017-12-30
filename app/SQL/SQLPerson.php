<?php

namespace Mymovielist\SQL;

use Illuminate\Database\Eloquent\Model;

class SQLPerson extends Model
{
    protected $table = 'persons';
    protected $connection = 'mysql';

    protected $fillable = ['name', 'surname', 'photo', 'birthday', 'biography'];
}