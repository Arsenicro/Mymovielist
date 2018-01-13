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
                <table class="table" style="margin-top: 50px">
                    <tr>
                        <th class="text-center" colspan="2" >
                            <strong style="font-size: 30px; text-align: center;">
                                Reviews
                            </strong>
                        </th>
                    </tr>
                    @foreach($reviews as $review)
                        <tr>
                            <th class="text-left" style="vertical-align: middle;" width="80%">
                                <img src="{{ $review['movie']->photo }}" width="50px" height="50px"> <a href="{{ route('movie',[$review['movie']->id]) }}">{{ $review['movie']->title }}</a>
                            </th>
                            <th class="text-center" style="vertical-align: middle" width="10%">
                                {{ $review['score'] }}
                            </th>
                            <th class="text-center" style="vertical-align: middle">
                                <a href="{{ route('review',[$review['movie']->id,$review['info']->id]) }}"><span class="glyphicon glyphicon-info-sign"></span></a>
                            </th>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>
@endsection
