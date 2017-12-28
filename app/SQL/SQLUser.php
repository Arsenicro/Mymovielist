<?php

namespace Mymovielist\SQL;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SQLUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'name', 'surname', 'email',
        'avatar', 'birthday', 'about', 'location',
        'last_online', 'gender', 'access', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
