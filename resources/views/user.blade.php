@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td width="10%" valign="top">
                <div style="float: left; margin-right: 20px;">
                    <img src="{{ $info->avatar }}" width="200px" height="300px"><br>
                    <br>
                    @if($info->name != null)
                        <div style="text-align: center">
                            <b>{{ $info->name }}</b>
                        </div>
                    @endif
                    @if($info->surname != null)
                        <div style="text-align: center">
                            <b>{{ $info->surname }}</b>
                        </div>
                    @endif
                    <br>
                    @if($info->birthday != null)
                        <div style="text-align: center">
                            Birthday: <i>{{ $info->birthday }}</i><br>
                        </div>
                    @endif
                </div>
            </td>
            <td class="text-center" valign="top" width="90%">
                <strong style="font-size: 30px; text-align: center;">
                    @if($info->gender == "male")
                        <span class="fa fa-mars" style="color:cadetblue" title="male"></span>
                    @elseif($info->gender == "female")
                        <span class="fa fa-venus" style="color:cadetblue" title="female"></span>
                    @elseif($info->gender == "other")
                        <span class="fa fa-genderless" style="color:cadetblue" title="other"></span>
                    @else
                        <span class="fa fa-genderless" style="color:cadetblue"></span>
                    @endif
                    {{ $info->login }} <a href="{{ route('editUser',[$info->login]) }}"><span class="glyphicon glyphicon-edit"
                                                                                              style="margin-left: 10px"></span></a>
                    <form action="{{ route('followOrNot',[$info->login]) }}" id="followOrNot"
                          method="post" style="display: inline-block">
                        {{ csrf_field() }}
                        <a href="#" onclick="document.getElementById('followOrNot').submit()">
                            @if(!$me)
                                @if($followed)
                                    <span class="glyphicon glyphicon-eye-close" style="margin-left: 10px"></span>
                                @else
                                    <span class="glyphicon glyphicon-eye-open" style="margin-left: 10px"></span>
                                @endif
                            @endif
                        </a>
                    </form>
                </strong>
                <div style="margin-top: 50px">{{ $info->about }}</div>
            </td>
        </tr>
    </table>
@endsection
