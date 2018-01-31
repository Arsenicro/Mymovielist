@extends('layouts.app')

@section('content')
    <div class="text-center">
        <strong style="font-size: 40px">{{ $movieinfo->title }} review <a href="{{ route('movie', [$movieinfo->id]) }}"><span class="glyphicon glyphicon-arrow-left"
                                                                                                    style="margin-left: 10px"></span></a>
            <a href="#" onclick="document.getElementById('createReview').submit()"><span
                        class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
        </strong>
    <br>
        <form action="{{ action('ReviewController@createReview', [$movieinfo->id]) }}" id="createReview"
              method="post" style="display: inline-block">
            {{ csrf_field() }}
            <textarea class="form-control" cols="130" rows="10" name="text"></textarea>
        </form>
    </div>
@endsection