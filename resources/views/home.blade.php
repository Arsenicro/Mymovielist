@extends('layouts.app')

@section('content')
<div class="container">
    <div class="text-center">
        <strong style="font-size: 40px">
            Recommendations:
        </strong>
    </div>
    <ul class="list-group">
        @foreach($recommends as $recommend)
            <li class="list-group-item"><img src="{{ $recommend->photo }}" width="50px" height="75px">
                <a href="{{ route('movie',[$recommend->id]) }}">{{ $recommend->title }}</a>
                <span class="badge">
                    <a href="{{ route('deleteRecommendation',[$recommend->id]) }}">
                        <span class="glyphicon glyphicon-trash" style="color: white"></span>
                    </a>
                </span>
            </li>
        @endforeach
    </ul>
    <a href="{{ route('resetRecommend') }}"><button class="btn btn-danger">Reset recommendations</button></a>
</div>
@endsection
