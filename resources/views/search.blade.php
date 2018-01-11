@extends('layouts.app')

@section('content')
    <form id="search" action="{{ action('SearchController@search') }}" method="get">
        <label for="title">Title:</label>
        <input class="form-control" rows="1" id="title" name="title" value="{{ $inputTitle }}">
        <label for="genres">Genre:</label>
        <select class="form-control" id="genres" name="genres">
            <option>All</option>
            @foreach($genres as $genre)
                @if($genre == $inputGenre)
                    <option selected>{{ $genre }}</option>
                @endif
                <option>{{ $genre }}</option>
            @endforeach
        </select>
        @if($watched)
            <label class="checkbox-inline"><input checked type="checkbox" name="watched">Show watched</label><br>
        @else
            <label class="checkbox-inline"><input type="checkbox" name="watched">Show watched</label><br>
        @endif

        <button type="submit" class="btn btn-info" style="margin-top: 50px">Search</button>
    </form>
    @if($searched)
        <div>
            <table class="table table-striped" width="100%">
                <tr>
                    <th width="70%" style="text-align: center">
                        Title
                        <a href="?sortby=title&order=asc{{ empty($_GET['filter']) ? '' : ("&filter=" . $_GET['filter']) }}">
                            <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                        </a>
                        <a href="?sortby=title&order=desc{{ empty($_GET['filter']) ? '' : ("&filter=" . $_GET['filter']) }}">
                            <span class="glyphicon glyphicon-sort-by-order"></span>
                        </a>
                    </th>
                    <th width="20%" style="text-align: center">
                        Production date
                        <a href="?sortby=prod_date&order=asc{{ empty($_GET['filter']) ? '' : ("&filter=" . $_GET['filter']) }}">
                            <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                        </a>
                        <a href="?sortby=prod_date&order=desc{{ empty($_GET['filter']) ? '' : ("&filter=" . $_GET['filter']) }}">
                            <span class="glyphicon glyphicon-sort-by-order"></span>
                        </a>
                    </th>
                    <th width="10%" style="text-align: center">
                        Score
                        <a href="?sortby=score&order=asc{{ empty($_GET['filter']) ? '' : ("&filter=" . $_GET['filter']) }}">
                            <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                        </a>
                        <a href="?sortby=score&order=desc{{ empty($_GET['filter']) ? '' : ("&filter=" . $_GET['filter']) }}">
                            <span class="glyphicon glyphicon-sort-by-order"></span>
                        </a>
                    </th>
                </tr>
                @foreach($movies as $movie)
                    <tr>
                        <th>
                            <img src="{{ $movie->photo }}" width="120px" height="120px" style="float: left; margin-right: 20px">
                            <a href="{{ route('movie',[$movie->id]) }}">{{ $movie->title }}</a>
                        </th>
                        <th style="text-align: center">
                            <i>{{ $movie->prod_date }}</i>
                        </th>
                        <th style="text-align: center">
                            {{ $movie->score }}
                        </th>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif
@endsection