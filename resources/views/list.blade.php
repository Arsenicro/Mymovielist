@extends('layouts.app')
@includeWhen($search,'search')
@includeWhen($movieList,'movielist', ['movies' => $result])
@includeWhen($userList,'userlist', ['users' => $result])
@includeWhen($personList,'personlist', ['persons' => $result])

@section('content')
    <div>
        @yield('searchbar')
    </div>
    <div>
        @yield('list')
    </div>
@endsection