<?php

namespace Mymovielist;

use Illuminate\Support\Facades\Auth;
use Mymovielist\Mongo\MongoEditHistory;

class EditHistory
{
    private $name;

    public function getName()
    {
        return $this->name;
    }

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function saveEdit($id, $field, $text)
    {
        $history            = new MongoEditHistory();
        $history->id        = $id;
        $history->userLogin = Auth::user()->login;
        $history->name      = $this->name;
        $history->field     = $field;
        $history->text      = $text;
        $history->save();
    }

    public function getEditHistory($id = null)
    {
        if ($id == null) {
            $history = MongoEditHistory::where('name', '=', $this->name)->get();
        } else {
            $history = MongoEditHistory::where('name', '=', $this->name)->where('id', '=', $id)->get();
        }
        $history->sortByDesc('created_at');

        return $history;
    }

    public function deleteOlderThan($date)
    {
        $history = MongoEditHistory::where('created_at', '<', $date)->get();
        foreach ($history as $record) {
            $record->delete();
        }
    }

    public static function deleteLog($id)
    {
        $edit = MongoEditHistory::find($id);
        if ($edit != null) {
            $edit->delete();
            return true;
        }
        return false;
    }
}