<?php

namespace Mymovielist;

use Carbon\Carbon;
use Mymovielist\NEO4J\NEO4JUser;
use Mymovielist\SQL\SQLUser;

class User
{

    private $login;
    private $sqlUser;
    private $neo4jUser;

    public function __construct($login, $sqlUser = null, $neo4JUser = null)
    {
        $this->login     = $login;
        $this->sqlUser   = $sqlUser ?? $this->getSqlUser();
        $this->neo4jUser = $neo4JUser ?? $this->getNeo4jUser();
    }

    public static function create(array $data)
    {
        $neo4jUser = NEO4JUser::create(['login' => $data['login']]);
        $sqlUser   = SQLUser::create($data);

        return new User($sqlUser->login, $sqlUser, $neo4jUser);
    }

    public function getNeo4jUser()
    {
        return $this->neo4jUser ?? NEO4JUser::where('login', $this->login)->first();
    }

    public function getSqlUser()
    {
        return $this->sqlUser ?? SQLUser::where('login', $this->login)->first();
    }

    public function getUserInfo(array $columns = null)
    {
        return $this->sqlUser->get($columns)->first();
    }

    public static function getUsersInfo($columns = null)
    {
        if ($columns != null) {
            return SQLUser::all($columns);
        }
        return SQLUser::all();
    }

    public function setAttribute($data)
    {
        $user = $this->sqlUser;
        $user->update($data);
    }

    public function follow(User $followedUser)
    {
        $user = $this->getNeo4jUser();

        $user->followers()->save($followedUser->getNeo4jUser(), ["since" => Carbon::now()->toDateString()]);
    }

    public function getFollowers()
    {
        return $this->getNeo4jUser()->followers()->get();
    }

    public function getFollowed()
    {
        return $this->getNeo4jUser()->followed()->get();
    }

    public function makeFan(Person $person)
    {
        $user = $this->getNeo4jUser();

        $user->isFan()->save($person->getNeo4jPerson());
    }

    public function getIdols()
    {
        return $this->getNeo4jUser()->isFan()->get();
    }

    public function likeIt(Movie $movie)
    {
        $user = $this->getNeo4jUser();

        $user->like()->save($movie->getNeo4jMovie());
    }

    public function getLikedMovies()
    {
        return $this->getNeo4jUser()->like()->get();
    }

    public function doesNotLike(Movie $movie)
    {
        $user = $this->getNeo4jUser()->first();

        $user->doesNotLike()->save($movie->getNeo4jMovie());
    }

    public function getUnlikedMovies()
    {
        return $this->getNeo4jUser()->doesNotLike()->get();
    }

    public function getReviews()
    {
        return $this->getNeo4jUser()->wroteReview()->get();
    }

    public function score(Movie $movie, $score)
    {
        $user = $this->getNeo4jUser();
        $user->score()->save($movie->getNeo4jMovie(), ['score' => $score]);

        $movie->newScore($score);
    }

    public function getScoredMovies()
    {
        return $this->getNeo4jUser()->score()->get();
    }

    public function getUserScore(Movie $movie)
    {
        $user = $this->getNeo4jUser();
        $edge = $user->score()->edge($movie->getNeo4jMovie());

        return $edge != null ? $edge->score : null;
    }

    public function likeMovie(Movie $movie)
    {
        $user = $this->getNeo4jUser();

        return $user->like()->edge($movie->getNeo4jMovie()) != null ? true : false;
    }

    public function scoreMovie(Movie $movie)
    {
        $user = $this->getNeo4jUser();

        return $user->score()->edge($movie->getNeo4jMovie()) != null ? true : false;
    }

    //Movie is watched iff is scored

    public function watchedMovie(Movie $movie)
    {
        return $this->scoreMovie($movie);
    }

    public function dislikeMovie(Movie $movie)
    {
        $user = $this->getNeo4jUser();

        return $user->doesNotLike()->edge($movie->getNeo4jMovie()) != null ? true : false;
    }

    //Return true iff user 'login' does not watch 'mid' and does not dislike it

    public function canRecommend(Movie $movie)
    {
        return !$this->watchedMovie($movie) && !$this->dislikeMovie($movie);
    }

    public function recommendByLikedAndGenres()
    {
        $liked = $this->getLikedMovies();

        $genres = $liked->map(
            function ($item, $key) {
                $movie = new Movie($item->mid);
                return $movie->getGenres();
            }
        )->collapse();

        $movies = $genres->map(
            function ($item, $key) {
                $genre = new Genre($item->name);
                return $genre->getAllMovies();
            }
        )->collapse()->unique();

        return $movies->filter(
            function ($item, $key) {
                $movie = new Movie($item->mid);
                return $this->canRecommend($movie);
            }
        );
    }

    public function recommendByFollowed()
    {
        $followed = $this->getFollowed();

        $movies = $followed->map(
            function ($item, $key) {
                $user = new User($item->login);
                return $user->getLikedMovies();
            }
        )->collapse()->unique();

        return $movies->filter(
            function ($item, $key) {
                $movie = new Movie($item->mid);
                return $this->canRecommend($movie);
            }
        );
    }

    public function recommend()
    {
        return $this->recommendByFollowed()->merge($this->recommendByLikedAndGenres())->unique()->values();
    }


}