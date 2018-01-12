@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td width="10%" valign="top">
                <div style="float: left; margin-right: 20px; text-align: center">
                    <img src="{{ $info->photo }}" width="200px" height="300px">
                    <form action="{{ action('PersonController@saveImage',[$info->id]) }}" id="photoSubmit" method="post" style="margin-top: 10px">
                        {{ csrf_field() }}
                        <textarea class="text-center form-control" rows="5" name="photo">{{ $info->photo }}</textarea><a
                                href="#" onclick="document.getElementById('photoSubmit').submit()"><span
                                    class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                    </form>
                    <div style="text-align: center">
                        <form action="{{ action('PersonController@saveBirthday',[$info->id]) }}" id="birthdaySubmit" method="post" style="margin-top: 10px">
                            {{ csrf_field() }}
                            <label for="birthday">Birth date:</label>
                            <textarea class="text-center form-control" rows="1" name="birthday">{{ $info->birthday }}</textarea><a
                                    href="#" onclick="document.getElementById('birthdaySubmit').submit()"><span
                                        class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                        </form>
                    </div>
                </div>
            </td>
            <td class="text-center" valign="top" width="90%">
                <strong style="text-align: center; font-size: 50px">
                    ID: {{ $info->id }} <a href="{{ route('person',[$info->id]) }}"><span class="glyphicon glyphicon-ok"
                                                                                           style="margin-left: 10px"></span></a>
                    <a href="{{ route('deletePerson',[$info->id]) }}" onclick="return confirm('Are you sure?')"><span class="glyphicon glyphicon-erase"></span></a>
                </strong>
                <strong style="font-size: 20px; text-align: center;">
                    <form action="{{ action('PersonController@saveName',[$info->id]) }}" id="nameSubmit" method="post" style="margin-top: 10px">
                        {{ csrf_field() }}
                        <label for="name">Name:</label>
                        <textarea class="text-center form-control" rows="1" name="name">{{ $info->name }}</textarea><a
                                href="#" onclick="document.getElementById('nameSubmit').submit()"><span
                                    class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                    </form>
                    <form action="{{ action('PersonController@saveSurname',[$info->id]) }}" id="surnameSubmit" method="post" style="margin-top: 10px">
                        {{ csrf_field() }}
                        <label for="surname">Surname:</label>
                        <textarea class="text-center form-control" rows="1" name="surname">{{ $info->surname }}</textarea><a
                                href="#" onclick="document.getElementById('surnameSubmit').submit()"><span
                                    class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                    </form>
                </strong>
                <div style="margin-top: 50px">
                    <form action="{{ action('PersonController@saveBiography',[$info->id]) }}" id="biographySubmit" method="post" style="margin-top: 10px">
                        {{ csrf_field() }}
                        <label for="biography">Biography:</label>
                        <textarea class="text-center form-control" rows="10" name="biography">{{ $info->biography }}</textarea><a
                                href="#" onclick="document.getElementById('biographySubmit').submit()"><span
                                    class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                    </form>
                </div>
                <table class="table" style="margin-top: 50px">
                    <tr>
                        <th class="text-center" colspan="2" >
                            <strong style="font-size: 30px; text-align: center;">
                                Played
                            </strong>
                        </th>
                    </tr>
                    @foreach($played as $movie)
                        <tr>
                            <th class="text-left">
                                <a href="{{ route('movie',[$movie['info']->id]) }}">{{ $movie['info']->title }}</a>
                            </th>
                            <th class="text-left">
                                {{ $movie['role'] }}
                            </th>
                        </tr>
                    @endforeach
                </table>
                <table class="table" style="margin-top: 50px">
                    <tr>
                        <th class="text-center">
                            <strong style="font-size: 30px; text-align: center;">
                                Directed
                            </strong>
                        </th>
                    </tr>
                    @foreach($directed as $movie)
                        <tr>
                            <th class="text-left">
                                <a href="{{ route('movie',[$movie['info']->id]) }}">{{ $movie['info']->title }}</a>
                            </th>
                        </tr>
                    @endforeach
                </table>
                <table class="table" style="margin-top: 50px">
                    <tr>
                        <th class="text-center">
                            <strong style="font-size: 30px; text-align: center;">
                                Wrote
                            </strong>
                        </th>
                    </tr>
                    @foreach($wrote as $movie)
                        <tr>
                            <th class="text-left">
                                <a href="{{ route('movie',[$movie['info']->id]) }}">{{ $movie['info']->title }}</a>
                            </th>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>
@endsection
