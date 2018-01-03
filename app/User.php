<?php

namespace Mymovielist;

use Carbon\Carbon;
use Mockery\Exception;
use Mymovielist\NEO4J\NEO4JUser;
use Mymovielist\SQL\SQLUser;

class User
{

    public static function create(array $data)
    {
        NEO4JUser::create(['login' => $data['login']]);

        return SQLUser::create($data);
    }

    public static function getNeo4jUser($login)
    {
        return NEO4JUser::where('login', $login)->first();
    }

    public static function getSqlUser($login)
    {
        return SQLUser::where('login', $login)->first();
    }

    public static function getUserInfo($login, array $columns = null)
    {
        return User::getSqlUser($login)->get($columns)->first();
    }

    public static function getUsersInfo($columns = null)
    {
        if ($columns != null) {
            return SQLUser::all($columns);
        }
        return SQLUser::all();
    }

    public static function setAttribute($id, $attribute, $value)
    {
        $user = SQLUser::where('id', $id)->first();

        switch ($attribute) {
            case "name":
                $user->update(['name' => $value]);
                break;
            case "surname":
                $user->update(['surname' => $value]);
                break;
            case "avatar":
                $user->update(['avatar' => $value]);
                break;
            case "birthday":
                $user->update(['birthday' => $value]);
                break;
            case "about":
                $user->update(['about' => $value]);
                break;
            case "location":
                $user->update(['location' => $value]);
                break;
            case "gender":
                $user->update(['gender' => $value]);
                break;
            default:
                throw new Exception("Wrong argument passed");
        }

    }

    public static function follow($login1, $login2)
    {
        $user1 = User::getNeo4jUser($login1);
        $user2 = User::getNeo4jUser($login2);

        $user1->followers()->save($user2, ["since" => Carbon::now()->toDateString()]);
    }

    public static function getFollowers($login)
    {
        return User::getNeo4jUser($login)->followers()->get();
    }

    public static function getFollowed($login)
    {
        return User::getNeo4jUser($login)->followed()->get();
    }

    public static function makeFan($login, $pid)
    {
        $person = Person::getNeo4jPerson($pid);
        $user   = User::getNeo4jUser($login);

        $user->isFan()->save($person);
    }

    public static function getIdols($login)
    {
        return User::getNeo4jUser($login)->isFan()->get();
    }

    public static function likeIt($login, $mid)
    {
        $movie = Movie::getNeo4jMovie($mid);
        $user  = User::getNeo4jUser($login);

        $user->like()->save($movie);
    }

    public static function getLikedMovies($login)
    {
        return User::getNeo4jUser($login)->like()->get();
    }

    public static function doesNotLike($login, $mid)
    {
        $movie = Movie::getNeo4jMovie($mid);
        $user  = User::getNeo4jUser($login)->first();

        $user->doesNotLike()->save($movie);
    }

    public static function getUnlikedMovies($login)
    {
        return User::getNeo4jUser($login)->doesNotLike()->get();
    }

    public static function getHisReviews($login)
    {
        return User::getNeo4jUser($login)->wroteReview()->get();
    }

    public static function score($login, $mid, $score)
    {
        $user       = User::getNeo4jUser($login);
        $neo4jMovie = Movie::getNeo4jMovie($mid);

        $user->score()->save($neo4jMovie, ['score' => $score]);

        Movie::newScore($mid, $score);
    }

    public static function getScoredMovies($login)
    {
        return User::getNeo4jUser($login)->score()->get();
    }

    public static function getUserScore($login, $mid)
    {
        $user  = User::getNeo4jUser($login);
        $movie = Movie::getNeo4jMovie($mid);
        $edge  = $user->score()->edge($movie);

        return $edge != null ? $edge->score : null;
    }

    public static function watched($login, $mid)
    {
        return User::getUserScore($login, $mid) != null ? true : false;
    }

    public static function dislike($login, $mid)
    {
        $user  = User::getNeo4jUser($login);
        $movie = Movie::getNeo4jMovie($mid);

        return $user->doesNotLike()->edge($movie) != null ? true : false;
    }

    //Return true iff user 'login' does not watch 'mid' and does not dislike it

    public static function canRecommend($login, $mid)
    {
        return !User::watched($login, $mid) && !User::dislike($login,$mid);
    }

    public static function recommendByLikedAndGenres($login)
    {
        $liked = User::getLikedMovies($login);

        $genres = $liked->map(function ($item,$key){
            return Movie::getGenres($item->mid);
        })->collapse();

        $movies = $genres->map(function ($item,$key){
           return Genre::getAllMovies($item->name);
        })->collapse()->unique();

        return $movies->filter(function ($item,$key) use ($login) {
            return User::canRecommend($login,$item->mid);
        });
    }

    public static function recommendByFollowed($login)
    {
        $followed = User::getFollowed($login);

        $movies = $followed->map(function ($item,$key){
            return User::getLikedMovies($item->login);
        })->collapse()->unique();

        return $movies->filter(function ($item,$key) use ($login) {
            return User::canRecommend($login,$item->mid);
        });
    }

    public static function recommend($login)
    {
        return User::recommendByFollowed($login)->merge(User::recommendByLikedAndGenres($login))->unique()->values();
    }


}