@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td valign="top" width="20%">
                <div style="text-align: center">
                    <img src="{{ $info->photo }}" width="200px" height="300px"><br>
                    <form action="{{ action('MovieController@saveImage',[$info->id]) }}" id="photoSubmit" method="post" style="margin-top: 10px">
                        {{ csrf_field() }}
                        <textarea class="text-center form-control" rows="5" name="img">{{ $info->photo }}</textarea><a
                                href="#" onclick="document.getElementById('photoSubmit').submit()"><span
                                    class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                    </form>
                    <div style="text-align: center">
                        Production date:<br>
                        <form action="{{ action('MovieController@saveDate',[$info->id]) }}" id="dateSubmit" method="post">
                            {{ csrf_field() }}
                            <textarea class="text-center form-control" rows="1" name="date">{{ $info->prod_date }}</textarea><a
                                    href="#" onclick="document.getElementById('dateSubmit').submit()"><span
                                        class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                        </form>
                    </div>
                    <table class="table">
                        <tr>
                            <th colspan="3" class="text-center">
                                <b>Reviews</b> <span class="glyphicon glyphicon-plus-sign" style="margin-left: 10px"></span>
                            </th>
                        </tr>
                        <tr>
                            <th class="text-center" width="90%" colspan="2">
                                User
                            </th>
                            <th class="text-center">
                                More
                            </th>
                        </tr>
                        @foreach($reviews as $review)
                            <tr>
                                <th class="text-right" width="10%">
                                    <img src="{{ $review['user']->avatar }}" width="25px" height="25px">
                                </th>
                                <th class="text-left" width="80%">
                                    <a href="{{ route('user',[$review['user']->login]) }}">{{ $review['user']->login }}</a>
                                </th>
                                <th class="text-center" style="vertical-align: middle">
                                    <a href="{{ route('review',[$info->id,$review['info']->id]) }}"><span
                                                class="glyphicon glyphicon-info-sign"></span></a>
                                </th>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </td>
            <td class="text-center" valign="top" width="90%">
                <div style="margin-left: 5%">
                    <strong style="font-size: 50px; text-align: center;">
                        ID: {{ $info->id }} <a href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-ok"
                                                                                             style="margin-left: 10px"></span></a>
                        <a href="{{ route('deleteMovie',[$info->id]) }}" onclick="return confirm('Are you sure?')"><span class="glyphicon glyphicon-erase"
                                                                         style="margin-left: 10px"></span></a><br>
                        <form action="{{ action('MovieController@saveTitle',[$info->id]) }}" id="titleSubmit" method="post">
                            {{ csrf_field() }}
                            <textarea class="text-center form-control" rows="1" name="title">{{ $info->title }}</textarea><a
                                    href="#" onclick="document.getElementById('titleSubmit').submit()"><span
                                        class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                        </form>
                    </strong>
                    <table class="table" style="margin-top: 40px">
                        <tr>
                            <th class="text-center" width="10%">
                                Score
                            </th>
                            <th class="text-center" width="10%">
                                Your Score
                            </th>
                            <th class="text-center border" width="80%">
                                Genres
                            </th>
                        </tr>
                        <tr class="info" style="font-size: 20px; font-family: 'Lato Light'">
                            <th class="text-center">
                                {{ $info->score }}<br>
                                <span style="font-size: 15px">{{ $info->number_of_scores }} users</span>
                            </th>
                            <th class="text-center" style="vertical-align: middle">
                                {{ $userscore }}
                            </th>
                            <th class="text-center" style="vertical-align: middle">
                                @foreach($genres as $genre)
                                    <span>
                                        <form action="{{ action('MovieController@deleteGenre',[$info->id]) }}" id="deleteGenre_{{ $genre->name }}"
                                              method="post" style="display: inline-block">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="name" value="{{ $genre->name }}">
                                            {{ $genre->name }}
                                            <a href="#" onclick="document.getElementById('deleteGenre_{{ $genre->name }}').submit()"><span
                                                        class="glyphicon glyphicon-remove" style="margin-left: 2px"></span></a>,
                                        </form>
                                    </span>
                                @endforeach
                                <form action="{{ action('MovieController@newGenre',[$info->id]) }}" id="newGenre"
                                      method="post" style="display: inline-block">
                                    {{ csrf_field() }}
                                    <textarea cols="10", rows="1" name="name"></textarea>
                                    <a href="#" onclick="document.getElementById('newGenre').submit()">
                                        <span class="glyphicon glyphicon-plus" style="margin-left: 10px"></span>
                                    </a>
                                </form>
                            </th>
                        </tr>
                    </table>
                    <div style="margin-top: 50px">
                        <form action="{{ action('MovieController@saveDesc',[$info->id]) }}" id="descSubmit" method="post">
                            {{ csrf_field() }}
                            <textarea cols="130" rows="10" name="desc">{{ $info->description }}</textarea><a
                                    href="#" onclick="document.getElementById('descSubmit').submit()"><span
                                        class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                        </form>
                    </div>
                    <div class="text-left" style="margin-top: 40px">
                        <b>Directed by:</b>
                        @foreach($directors as $director)
                            <span>
                                <form action="{{ action('MovieController@deleteDirector',[$info->id]) }}"
                                      id="deleteDirector{{ $director['info']->id }}"
                                      method="post" style="display: inline-block">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="pid" value="{{ $director['info']->id }}">
                                    <a href="{{ route('person',[$director['info']->id]) }}">{{ $director['info']->name }} {{ $director['info']->surname }}</a>
                                    <a href="#" onclick="document.getElementById('deleteDirector{{ $director['info']->id }}').submit()"><span
                                                class="glyphicon glyphicon-remove" style="margin-left: 2px"></span></a>,
                                </form>
                            </span>
                        @endforeach
                        <form action="{{ action('MovieController@newDirector',[$info->id]) }}" id="newDirector"
                              method="post" style="display: inline-block">
                            {{ csrf_field() }}
                            <textarea cols="3" , rows="1" name="pid"></textarea>
                            <a href="#" onclick="document.getElementById('newDirector').submit()">
                                <span class="glyphicon glyphicon-plus" style="margin-left: 10px"></span>
                            </a>
                        </form>
                        <br>
                        <b>Wrote by:</b>
                        @foreach($writers as $writer)
                            <span>
                                <form action="{{ action('MovieController@deleteWriter',[$info->id]) }}" id="deleteWriter{{ $writer['info']->id }}"
                                      method="post" style="display: inline-block">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="pid" value="{{ $writer['info']->id }}">
                                    <a href="{{ route('person',[$writer['info']->id]) }}">{{ $writer['info']->name }} {{ $writer['info']->surname }}</a>
                                    <a href="#" onclick="document.getElementById('deleteWriter{{ $writer['info']->id }}').submit()"><span
                                                class="glyphicon glyphicon-remove" style="margin-left: 2px"></span></a>,
                                </form>
                            </span>
                        @endforeach
                        <form action="{{ action('MovieController@newWriter',[$info->id]) }}" id="newWriter"
                              method="post" style="display: inline-block">
                            {{ csrf_field() }}
                            <textarea cols="3" , rows="1" name="pid"></textarea>
                            <a href="#" onclick="document.getElementById('newWriter').submit()">
                                <span class="glyphicon glyphicon-plus" style="margin-left: 10px"></span>
                            </a>
                        </form>
                    </div>
                    <table class="table" style="margin-top: 50px">
                        <tr>
                            <th class="text-center" colspan="3">
                                <strong style="font-size: 30px; text-align: center;">
                                    Cast
                                </strong>
                            </th>
                        </tr>
                    </table>
                    @foreach($casts as $cast)
                        <table class="table">
                            <tr>
                                <th style="vertical-align: middle" width="45%">
                                    <img src="{{ $cast['info']->photo }}" width="50px" height="50px">
                                    <form action="{{ action('MovieController@editRole',[$info->id]) }}" id="roleSubmit{{ $cast['info']->id }}"
                                          method="post"
                                          style="display: inline">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="personid" value="{{ $cast['info']->id }}">
                                        <textarea rows="1" name="role">{{ $cast['role'] }}</textarea><a
                                                href="#" onclick="document.getElementById('roleSubmit{{ $cast['info']->id }}').submit()"><span
                                                    class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                                    </form>
                                </th>
                                <th class="text-center" style="vertical-align: middle" width="45%">

                                    <a href="{{ route('person',[$cast['info']->id]) }}">{{ $cast['info']->name }} {{ $cast['info']->surname }}</a>

                                </th>
                                <th class="text-center" style="vertical-align: middle" width="10%">
                                    <span>
                                         <form action="{{ action('MovieController@deleteCast',[$info->id]) }}"
                                               id="deleteCast{{ $cast['info']->id }}"
                                               method="post" style="display: inline-block">
                                        {{ csrf_field() }}
                                             <input type="hidden" name="pid" value="{{ $cast['info']->id }}">
                                        <a href="#" onclick="document.getElementById('deleteCast{{ $cast['info']->id }}').submit()"><span
                                                    class="glyphicon glyphicon-remove" style="margin-left: 2px"></span></a>
                                        </form>
                                    </span>
                                </th>
                            </tr>
                        </table>
                    @endforeach
                    <form action="{{ action('MovieController@newCast',[$info->id]) }}" id="addRole" method="post" style="display: inline">
                        <table class="table">
                            <tr>
                                {{ csrf_field() }}
                                <th style="vertical-align: middle" width="45%">
                                    <label for="textarea">Role:</label> <textarea rows="1" name="role"></textarea>
                                </th>
                                <th class="text-center" style="vertical-align: middle" width="45%">
                                    <label for="textarea">Person ID:</label> <textarea rows="1" cols="3" name="personid"></textarea>
                                </th>
                                <th class="text-center" style="vertical-align: middle" width="10%">
                                    <a href="#" onclick="document.getElementById('addRole').submit()"><span
                                                class="glyphicon glyphicon-plus" style="margin-left: 10px"></span></a>
                                </th>
                            </tr>
                        </table>
                    </form>
                </div>
            </td>
        </tr>
    </table>
@endsection