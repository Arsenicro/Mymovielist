@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center">
        <strong style="font-size: 40px">
            List of movies:
        </strong>
    </div>
    <ul class="list-group">
        @foreach($movies as $movie)
            <li class="list-group-item"><img src="{{ $movie->photo }}" width="60px" height="60px">
                <a href="{{ route('movie',[$movie->id]) }}">{{ $movie->title }}</a>
            </li>
        @endforeach
    </ul>
 </div>
@endsection
