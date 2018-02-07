@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('moviesEdits') }}">
            <button class="btn btn-info">Movie Edit History</button>
        </a>
        <a href="{{ route('usersEdits') }}">
            <button class="btn btn-info">Users Edit History</button>
        </a>
        <a href="{{ route('personsEdits') }}">
            <button class="btn btn-info">Persons Edit History</button>
        </a>
        <a href="{{ route('searchStats') }}">
            <button class="btn btn-info">Search Stats</button>
        </a>
        <form class="form-inline" action="{{ route('clearLogs') }}" style="display: inline" method="post">
            {{ csrf_field() }}
            <button class="btn btn-danger" type="submit">Delete older than {{ env('DELETE_OLDER_THAN_DAYS',30) }} days</button>
        </form>
    </div>
    <div style="margin-top: 50px">
 @if($result != null)
            <textarea class="form-control" rows="20" readonly>{{ $result }}</textarea>
     @endif
    </div>
@endsection
