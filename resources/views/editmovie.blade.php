@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td class="text-center" valign="top" width="20%">
                <strong style="text-align: center; font-size: 50px">
                    ID: {{ $info->id }} <a href="{{ route('movie', [$info->id]) }}"><span class="glyphicon glyphicon-arrow-left"
                                                                                          style="margin-left: 10px"></span></a>
                    <a href="{{ route('deleteMovie',[$info->id]) }}" onclick="return confirm('Are you sure?')"><span class="glyphicon glyphicon-erase"></span></a>
                </strong>

                <form class="text-center" action="{{ action('MovieController@save',[$info->id]) }}" id="save" method="post">
                    {{ csrf_field() }}
                    <table class="table text-center">
                        <tr>
                            <td>
                                Title
                            </td>
                            <td>
                                <input type="hidden" name="oldtitle" value="{{ $info->title }}">
                                <textarea cols="50" rows="1" name="title">{{ $info->title }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Production date
                            </td>
                            <td>
                                <input type="hidden" name="oldprod_date" value="{{ $info->prod_date }}">
                                <textarea cols="50" rows="1" name="prod_date">{{ $info->prod_date }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Image
                            </td>
                            <td>
                                <input type="hidden" name="oldimage" value="{{ $info->photo }}">
                                <textarea cols="50" rows="1" name="image">{{ $info->photo }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Description
                            </td>
                            <td>
                                <input type="hidden" name="olddescription" value="{{ $info->description }}">
                                <textarea cols="50" rows="5" name="description">{{ $info->description }}</textarea>
                            </td>
                        </tr>
                    </table>
                    <strong style="font-size: 50px">
                        <a href="#" onclick="document.getElementById('save').submit()">
                            <span class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span>
                        </a>
                    </strong>
                </form>

                <table class="table" style="margin-top: 40px">
                    <th class="text-center border">
                        Genres
                    </th>
                    <tr class="info" style="font-size: 20px; font-family: 'Lato Light'">
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
                                <textarea cols="10" , rows="1" name="name"></textarea>
                                <a href="#" onclick="document.getElementById('newGenre').submit()">
                                    <span class="glyphicon glyphicon-plus" style="margin-left: 10px"></span>
                                </a>
                            </form>
                        </th>
                    </tr>
                </table>
                <div style="margin-top: 50px">

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

                                {{ $cast['role'] }}

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