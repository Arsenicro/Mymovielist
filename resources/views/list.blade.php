@extends('layouts.app')

@section('content')
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
                        <a href="{{ route('movie',$movie->title) }}">{{ $movie->title }}</a>
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
@endsection
