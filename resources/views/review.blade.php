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
                        <td class="text-center" style="vertical-align: middle" width="50%">
                            <a href="{{ route('user',[$userinfo->login]) }}">{{ $userinfo->login }}</a>
                        </td>
                        <td class="text-right" style="vertical-align: middle" width="10%">
                            <b>{{ $score }}</b>
                        </td>
                        <td class="text-right" style="vertical-align: middle" width="20%">
                            {{ $reviewinfo->created_at }}
                        </td>
                        <td  class="text-right" style="vertical-align: middle" width="10%">
                            <form action="{{ route('deleteReview',[$movieinfo->id,$reviewinfo->id]) }}" id="deleteReview"
                                  method="post" style="display: inline-block">
                                {{ csrf_field() }}
                                <a href="#" onclick="document.getElementById('deleteReview').submit()">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </a>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center" valign="center" colspan="4">
                            {{ $reviewinfo->text }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection