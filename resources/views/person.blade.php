@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td width="10%" valign="top">
                <div style="float: left; margin-right: 20px;">
                    <img src="{{ $info->photo }}" width="200px" height="300px"><br>
                    <br>
                    <div style="text-align: center">
                        Birth date: {{ $info->birthday }}
                    </div>
                </div>
            </td>
            <td class="text-center" valign="top" width="90%">
                <strong style="font-size: 50px; text-align: center;">
                    {{ $info->name }} {{ $info->surname }} <span class="glyphicon glyphicon-edit" style="margin-left: 10px"></span>
                </strong>
                <div style="margin-top: 50px">{{ $info->biography }}</div>
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
