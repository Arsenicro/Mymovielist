@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td width="10%" valign="top">
                <div style="float: left; margin-right: 20px;">
                    <img src="{{ $info->avatar }}" width="200px" height="300px"><br>
                    <br>
                    @if($info->name != null && $info->surname)
                        <div style="text-align: center">
                            <b>{{ $info->name }} {{ $info->surname }}</b><br>
                        </div>
                    @elseif($info->name != null)
                        <div style="text-align: center">
                            <b>{{ $info->name }}</b><br>
                        </div>
                    @elseif($info->surname != null)
                        <div style="text-align: center">
                            <b>{{ $info->surname }}</b><br>
                        </div>
                    @endif
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
                    @elseif($info->gender != null)
                        <span class="fa fa-genderless" style="color:cadetblue" title="{{ $info->gender }}"></span>
                    @else
                        <span class="fa fa-genderless" style="color:cadetblue"></span>
                    @endif
                    {{ $info->login }} <span class="glyphicon glyphicon-edit" style="margin-left: 10px"></span>  <span class="glyphicon glyphicon-check" style="margin-left: 10px"></span>
                </strong>
                <div style="margin-top: 50px">{{ $info->about }}</div>
            </td>
        </tr>
    </table>
@endsection
