@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td valign="top" width="20%">
                <div style="text-align: center">
                    <img src="{{ $info->photo }}" width="200px" height="300px"><br>
                    <br>
                    <div style="text-align: center">
                        Production date:<br>
                        {{ $info->prod_date }}
                    </div>
                    <table class="table">
                        <tr>
                            <th colspan="3" class="text-center">
                                <b>Reviews</b> <a href="{{ route('newReview',[$info->id]) }}"><span class="glyphicon glyphicon-plus-sign" style="margin-left: 10px"></span></a>
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
                                    <img src="{{ $review['user']->avatar }}" width="20px" height="30px">
                                </th>
                                <th class="text-left" width="80%">
                                    <a href="{{ route('user',[$review['user']->login]) }}">{{ $review['user']->login }}</a>
                                </th>
                                <th class="text-center" style="vertical-align: middle">
                                    <a href="{{ route('review',[$info->id,$review['info']->id]) }}"><span class="glyphicon glyphicon-info-sign"></span></a>
                                </th>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </td>
            <td class="text-center" valign="top" width="90%">
                <div style="margin-left: 5%">
                    <strong style="font-size: 50px; text-align: center;">
                        {{ $info->title }} <a href="{{ route('editMovie',[$info->id]) }}"><span class="glyphicon glyphicon-edit" style="margin-left: 10px"></span></a>
                        <form action="{{ action('MovieController@likeOrNot',[$info->id]) }}" id="likeOrNot"
                              method="post" style="display: inline-block">
                            {{ csrf_field() }}
                            <a href="#" onclick="document.getElementById('likeOrNot').submit()">
                                @if($liked)
                                    <span class="fa fa-thumbs-down" style="margin-left: 10px"></span>
                                @else
                                    <span class="fa fa-thumbs-up" style="margin-left: 10px"></span>
                                @endif
                            </a>
                        </form>
                    </strong>
                    <table class="table" style="margin-top: 40px">
                        <tr>
                            <th class="text-center" width="10%">
                                Score
                            </th>
                            <th class="text-center" width="15%">
                                Your Score
                            </th>
                            <th class="text-center border" width="75%">
                                Genres
                            </th>
                        </tr>
                        <tr class="info" style="font-size: 20px; font-family: 'Lato Light'">
                            <th class="text-center">
                                {{ $info->score }}<br>
                                <span style="font-size: 15px">{{ $info->number_of_scores }} users</span>
                            </th>
                            <th class="text-center" style="vertical-align: middle">
                                <form action="{{ action('MovieController@saveScore',[$info->id]) }}" id="saveScore"
                                      method="post" style="display: inline-block">
                                    {{ csrf_field() }}
                                    <select class="form-control" name="score">
                                        <option>N/A</option>
                                        @for($i = 1; $i <= 10; $i++)
                                            @if($userscore == $i)
                                                <option selected="selected">{{ $i }}</option>
                                            @else
                                                <option>{{ $i }}</option>
                                            @endif
                                        @endfor
                                    </select>
                                    <a href="#" onclick="document.getElementById('saveScore').submit()">
                                        <span class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span>
                                    </a>
                                </form>
                            </th>
                            <th class="text-center" style="vertical-align: middle">
                                @foreach($genres as $i=>$genre)
                                    @if($i < count($genres) - 1)
                                        {{ $genre->name }},
                                    @else
                                        {{ $genre->name }}
                                    @endif
                                @endforeach
                            </th>
                        </tr>
                    </table>
                    <div style="margin-top: 50px">{{ $info->description }} </div>
                    <div class="text-left" style="margin-top: 40px">
                        <b>Directed by:</b>
                        @foreach($directors as $i=>$director)
                            @if($i < count($directors) - 1)
                                <a href="{{ route('person',[$director['info']->id]) }}">{{ $director['info']->name }} {{ $director['info']->surname }}</a>,
                            @else
                                <a href="{{ route('person',[$director['info']->id]) }}">{{ $director['info']->name }} {{ $director['info']->surname }}</a>
                            @endif
                        @endforeach
                        <br>
                        <b>Wrote by:</b>
                        @foreach($writers as $i=>$writer)
                            @if($i < count($writers) - 1)
                                <a href="{{ route('person',[$writer['info']->id]) }}">{{ $writer['info']->name }} {{ $writer['info']->surname }}</a>,
                            @else
                                <a href="{{ route('person',[$writer['info']->id]) }}">{{ $writer['info']->name }} {{ $writer['info']->surname }}</a>
                            @endif
                        @endforeach
                    </div>
                    <table class="table" style="margin-top: 50px">
                        <tr>
                            <th class="text-center" colspan="2" >
                                <strong style="font-size: 30px; text-align: center;">
                                    Cast
                                </strong>
                            </th>
                        </tr>
                        @foreach($casts as $cast)
                            <tr>
                                <th>
                                    <img src="{{ $cast['info']->photo }}" width="50px" height="75px"> {{ $cast['role'] }}
                                </th>
                                <th class="text-center" style="vertical-align: middle">
                                    <a href="{{ route('person',[$cast['info']->id]) }}">{{ $cast['info']->name }} {{ $cast['info']->surname }}</a>
                                </th>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </td>
        </tr>
    </table>
@endsection
