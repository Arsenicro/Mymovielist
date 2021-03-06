<?php

namespace Mymovielist;

use Mymovielist\NEO4J\NEO4JPerson;
use Mymovielist\SQL\SQLPerson;

class Person
{
    private $pid;
    private $sqlPerson;
    private $neo4jPerson;

    public function __construct($pid, $sqlPerson = null, $neo4jPerson = null)
    {
        $this->pid         = intval($pid);
        $this->sqlPerson   = $sqlPerson ?? $this->getSqlPerson();
        $this->neo4jPerson = $neo4jPerson ?? $this->getNeo4jPerson();
    }

    public function exist()
    {
        if ($this->sqlPerson == null || $this->neo4jPerson == null) {
            return false;
        }

        return true;
    }

    public static function nameAndSurnameExist($name, $surname)
    {
        $persons = Person::getPersonsInfo();

        foreach ($persons as $person) {
            if ($person->name == $name && $person->surname == $surname) {
                return true;
            }
        }
    }

    public function delete()
    {
        if (!$this->exist()) {
            return false;
        }

        return $this->getNeo4jPerson()->delete() && $this->getSqlPerson()->delete();
    }

    public static function create(array $data)
    {
        $sqlPerson   = SQLPerson::create($data);
        $neo4jPerson = NEO4JPerson::create(['pid' => $sqlPerson->id]);

        return new Person($sqlPerson->id, $sqlPerson, $neo4jPerson);
    }

    public function setAttribute($data)
    {
        $person = $this->getSqlPerson();
        $person->update($data);
    }

    public function getNeo4jPerson()
    {
        return $this->neo4jPerson ?? NEO4JPerson::where('pid', $this->pid)->first();
    }

    public function getSqlPerson()
    {
        return $this->sqlPerson ?? SQLPerson::where('id', $this->pid)->first();
    }

    public function getPersonInfo()
    {
        return $this->sqlPerson;
    }

    public static function getPersonsInfo($columns = null)
    {
        if ($columns != null) {
            return SQLPerson::all($columns);
        }
        return SQLPerson::all();
    }

    public function fans()
    {
        return $this->neo4jPerson->hasFan()->get();
    }

    public function numberOfFans()
    {
        return $this->getNeo4jPerson()->hasFan()->get()->count();
    }

    public function wrote()
    {
        return $this->neo4jPerson->isWriter()->get();
    }

    public function directed()
    {
        return $this->neo4jPerson->isDirector()->get();
    }

    public function played()
    {
        return $this->neo4jPerson->isStar()->get();
    }

    public function likedBy(User $user)
    {
        return $this->getNeo4jPerson()->hasFan()->edge($user->getNeo4jUser()) != null;
    }

    public function getRole(Movie $movie)
    {
        $person     = $this->neo4jPerson;
        $neo4jMovie = $movie->getNeo4jMovie();
        $edge       = $person->isStar()->edge($neo4jMovie);

        return $edge != null ? $edge->role : null;
    }

    public function saveRole(Movie $movie, $role)
    {
        $person     = $this->neo4jPerson;
        $neo4jMovie = $movie->getNeo4jMovie();
        $edge       = $person->isStar()->edge($neo4jMovie);

        if ($edge != null) {
            $edge->role = $role;
            $edge->save();
            return true;
        }

        return false;
    }
}