@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td width="10%" valign="top">
                <div style="float: left; margin-right: 20px; text-align: center">
                    <img src="{{ $info->photo }}" width="200px" height="300px"><br>
                    <form action="{{ action('MovieController@saveImage',[$info->id]) }}" id="photoSubmit" method="post">
                        {{ csrf_field() }}
                        <textarea class="text-center" rows="1" name="img">{{ $info->photo }}</textarea><a
                                href="#" onclick="document.getElementById('photoSubmit').submit()"><span
                                    class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                    </form>
                    <div style="text-align: center">
                        Production date:<br>
                        <form action="{{ action('MovieController@saveDate',[$info->id]) }}" id="dateSubmit" method="post">
                            {{ csrf_field() }}
                            <textarea class="text-center" rows="1" name="date">{{ $info->prod_date }}</textarea><a
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
            <td class="text-center" valign="top">
                <strong style="font-size: 50px; text-align: center;">
                    ID: {{ $info->id }} <a href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-ok"
                                                                                         style="margin-left: 10px"></span></a><br>
                    <form action="{{ action('MovieController@saveTitle',[$info->id]) }}" id="titleSubmit" method="post">
                        {{ csrf_field() }}
                        <textarea class="text-center" rows="1" name="title">{{ $info->title }}</textarea><a
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
                            @foreach($genres as $i=>$genre)
                                @if($i < count($genres) - 1)
                                    {{ $genre->name }} <a href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-remove"
                                                                                                        style="margin-left: 2px"></span></a>,
                                @else
                                    {{ $genre->name }} <a href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-plus"
                                                                                                        style="margin-left: 10px"></span></a>
                                @endif
                            @endforeach
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
                    @foreach($directors as $i=>$director)
                        @if($i < count($directors) - 1)
                            <a href="{{ route('person',[$director['info']->id]) }}">{{ $director['info']->name }} {{ $director['info']->surname }}</a>
                            <a href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-remove" style="margin-left: 4px"></span></a>,
                        @else
                            <a href="{{ route('person',[$director['info']->id]) }}">{{ $director['info']->name }} {{ $director['info']->surname }}</a>
                            <a href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-remove" style="margin-left: 4px"></span></a>
                        @endif
                    @endforeach
                    <a href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-plus" style="margin-left: 8px"></span></a>
                    <br>
                    <b>Wrote by:</b>
                    @foreach($writers as $i=>$writer)
                        @if($i < count($writers) - 1)
                            <a href="{{ route('person',[$writer['info']->id]) }}">{{ $writer['info']->name }} {{ $writer['info']->surname }}</a> <a
                                    href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-remove" style="margin-left: 4px"></span></a>
                            ,
                        @else
                            <a href="{{ route('person',[$writer['info']->id]) }}">{{ $writer['info']->name }} {{ $writer['info']->surname }}</a> <a
                                    href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-remove" style="margin-left: 4px"></span></a>
                        @endif
                    @endforeach
                    <a href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-plus" style="margin-left: 8px"></span></a>

                </div>
                <table class="table" style="margin-top: 50px">
                    <tr>
                        <th class="text-center" colspan="3">
                            <strong style="font-size: 30px; text-align: center;">
                                Cast
                            </strong>
                        </th>
                    </tr>
                    @foreach($casts as $cast)
                        <tr>
                            <th style="vertical-align: middle">
                                <img src="{{ $cast['info']->photo }}" width="50px" height="50px">
                                <form action="{{ action('MovieController@editRole',[$info->id]) }}" id="roleSubmit" method="post" style="display: inline">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="personid" value="{{ $cast['info']->id }}">
                                    <textarea rows="1" name="role">{{ $cast['role'] }}</textarea><a
                                            href="#" onclick="document.getElementById('roleSubmit').submit()"><span
                                                class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                                </form>
                            </th>
                            <th class="text-center" style="vertical-align: middle">
                                <a href="{{ route('person',[$cast['info']->id]) }}">{{ $cast['info']->name }} {{ $cast['info']->surname }}</a>
                            </th>
                            <th class="text-center" style="vertical-align: middle">
                                <a href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-remove" style="margin-left: 10px"></span></a>
                            </th>
                        </tr>
                    @endforeach
                    <tr>
                        <th style="vertical-align: middle">
                            <label for="textarea">Role:</label><textarea rows="1"></textarea>
                        </th>
                        <th class="text-center" style="vertical-align: middle">
                            <label for="textarea">Person ID:</label> <textarea rows="1" cols="3"></textarea>
                        </th>
                        <th class="text-center" style="vertical-align: middle">
                            <a href="{{ route('movie',[$info->id]) }}"><span class="glyphicon glyphicon-plus" style="margin-left: 10px"></span></a>
                        </th>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection
