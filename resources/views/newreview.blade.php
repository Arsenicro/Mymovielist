@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td width="10%" valign="top">
                <div style="float: left; margin-right: 20px;">
                    <img src="{{ $movieinfo->photo }}" width="200px" height="300px"><br>
                    <br>
                    <div style="text-align: center">
                        Production date: {{ $movieinfo->prod_date }}
                    </div>
                    <div class="text-center">
                        <b><a href="{{ route('movie',[$movieinfo->id]) }}">Details</a></b>
                    </div>
                </div>
            </td>
            <td class="text-center" valign="top" width="90%">
                <table class="table">
                    <tr>
                        <td class="text-left" width="10%">
                            <img src="{{ $userinfo->avatar }}" width="50px" height="50px">
                        </td>
                        <td class="text-center" style="vertical-align: middle" width="60%">
                            <a href="{{ route('user',[$userinfo->login]) }}">{{ $userinfo->login }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" valign="center" colspan="4">
                            <form action="{{ action('ReviewController@createReview', [$movieinfo->id]) }}" id="createReview"
                                  method="post" style="display: inline-block">
                                {{ csrf_field() }}
                                <textarea class="form-control" cols="130" rows="10" name="text"></textarea>
                                <a href="#" onclick="document.getElementById('createReview').submit()"><span
                                            class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                            </form>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection