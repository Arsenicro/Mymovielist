<?php

namespace Mymovielist;

use Illuminate\Support\Facades\Auth;
use Mymovielist\Mongo\MongoSearchStats;

class SearchStats
{
    public function saveSearch($text, $name, $watched = null, $genre = null)
    {
        $search       = new MongoSearchStats();
        $search->text = $text;
        $search->name = $name;
        if (Auth::user() != null) {
            $search->userLogin = Auth::user()->login;
        } else {
            $search->userLogin = "guest";
        }
        if ($watched != null) {
            $search->watched = $watched ? 'true' : 'false';
        }
        if ($genre != null) {
            $search->genre = $genre;
        }
        $search->save();
    }

    public function getSearchStats($name = null)
    {
        if ($name == null) {
            $search = MongoSearchStats::all();
        } else {
            $search = MongoSearchStats::where('name', '=', $name)->get();
        }

        $search->sortByDesc('created_at');

        return $search;
    }

    public function deleteOlderThan($date)
    {
        $search = MongoSearchStats::where('created_at', '<', $date)->get();
        foreach ($search as $record) {
            $record->delete();
        }
    }

    public static function deleteStat($id)
    {
        $search = MongoSearchStats::find($id);
        if ($search != null) {
            $search->delete();
            return true;
        }
        return false;
    }
}