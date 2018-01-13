<?php

namespace Mymovielist\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Mymovielist\EditHistory;
use Mymovielist\SearchStats;

class LogController extends Controller
{
    public function log()
    {
        return view('log', ['result' => null]);
    }

    public function viewMoviesEditHistory()
    {
        $editHistory = new EditHistory('movie');
        $results     = $editHistory->getEditHistory();
        $print       = "";
        foreach ($results as $result) {
            $print .= "{id:" . $result->id . "},{edit_by:" . $result->userLogin . "},{field:" . $result->field . "},{old_text:" . $result->text . "}" . PHP_EOL;
        }
        return view('log', ['result' => $print]);
    }

    public function viewUsersEditHistory()
    {
        $editHistory = new EditHistory('user');
        $results     = $editHistory->getEditHistory();
        $print       = "";
        foreach ($results as $result) {
            $print .= "{login:" . $result->id . "},{edit_by:" . $result->userLogin . "},{field:" . $result->field . "},{old_text:" . $result->text . "}" . PHP_EOL;
        }
        return view('log', ['result' => $print]);
    }

    public function viewPersonsEditHistory()
    {
        $editHistory = new EditHistory('person');
        $results     = $editHistory->getEditHistory();
        $print       = "";
        foreach ($results as $result) {
            $print .= "{id:" . $result->id . "},{edit_by:" . $result->userLogin . "},{field:" . $result->field . "},{old_text:" . $result->text . "}" . PHP_EOL;
        }
        return view('log', ['result' => $print]);
    }

    public function viewSearch()
    {
        $searchStats = new SearchStats();
        $results     = $searchStats->getSearchStats();
        $print       = "";
        foreach ($results as $result) {
            $print .= "{table: " . $result->name . "},{text:" . $result->text . "},{user:" . $result->userLogin . "}";
            if ($result->name == "movie") {
                $watched = $result->watched ?? "false";
                $print   .= "{watched:" . $watched . "},{genre:" . $result->genre . "}" . PHP_EOL;
            } else {
                $print .= PHP_EOL;
            }
        }
        return view('log', ['result' => $print]);
    }

    public function clear()
    {
        $editMovies  = new EditHistory('movie');
        $editPersons = new EditHistory('person');
        $editUser    = new EditHistory('user');
        $searchStats = new SearchStats();

        $date = Carbon::now()->subDays(30);

        $editMovies->deleteOlderThan($date);
        $editPersons->deleteOlderThan($date);
        $editUser->deleteOlderThan($date);
        $searchStats->deleteOlderThan($date);

        return redirect()->route('logs')->with('message','Deleted!');
    }
}
