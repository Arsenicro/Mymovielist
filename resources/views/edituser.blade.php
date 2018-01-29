@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td class="text-center" valign="top" width="20%">
                <strong style="text-align: center; font-size: 50px">
                    {{ $info->login }} <a href="{{ route('user', [$info->login]) }}"><span class="glyphicon glyphicon-arrow-left"
                                                                                        style="margin-left: 10px"></span></a>
                    <a href="{{ route('deleteUser',[$info->login]) }}" onclick="return confirm('Are you sure?')"><span
                                class="glyphicon glyphicon-erase"></span></a>
                </strong>
                <form class="text-center" action="{{ action('UserController@save',[$info->login]) }}" id="save" method="post">
                    {{ csrf_field() }}
                    <table class="table text-center">
                        <tr>
                            <td>
                                Name
                            </td>
                            <td>
                                <input type="hidden" name="oldname" value="{{ $info->name }}">
                                <textarea cols="50" rows="1" name="name">{{ $info->name }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Surname
                            </td>
                            <td>
                                <input type="hidden" name="oldsurname" value="{{ $info->surname }}">
                                <textarea cols="50" rows="1" name="surname">{{ $info->surname }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Birthday
                            </td>
                            <td>
                                <input type="hidden" name="oldbirthday" value="{{ $info->birthday }}">
                                <textarea cols="50" rows="1" name="birthday">{{ $info->birthday }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Avatar
                            </td>
                            <td>
                                <input type="hidden" name="oldavatar" value="{{ $info->avatar }}">
                                <textarea cols="50" rows="1" name="avatar">{{ $info->avatar }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                About
                            </td>
                            <td>
                                <input type="hidden" name="oldabout" value="{{ $info->about }}">
                                <textarea cols="50" rows="5" name="about">{{ $info->about }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Gender
                            </td>
                            <td>
                                <input type="hidden" name="oldgender" value="{{ $info->gender }}">
                                <select class="form-control" name="gender">
                                    @if($info->gender == "Male")
                                        <option selected>Male</option>
                                        <option>Female</option>
                                        <option>Other</option>
                                    @elseif($info->gender == "Female")
                                        <option>Male</option>
                                        <option selected>Female</option>
                                        <option>Other</option>
                                    @else
                                        <option>Male</option>
                                        <option>Female</option>
                                        <option selected>Other</option>
                                    @endif
                                </select>
                            </td>
                        </tr>
                        @if($isAdmin)
                            <td>
                                Access:
                            </td>
                            <td>
                                <input type="hidden" name="oldaccess" value="{{ $info->access }}">
                                <select class="form-control" name="access">
                                    @if($info->access == "a")
                                        <option selected>Admin</option>
                                        <option>Moderator</option>
                                        <option>User</option>
                                    @elseif($info->access == "m")
                                        <option>Admin</option>
                                        <option selected>Moderator</option>
                                        <option>User</option>
                                    @else
                                        <option>Admin</option>
                                        <option>Moderator</option>
                                        <option selected>User</option>
                                    @endif
                                </select>
                            </td>
                        @endif
                    </table>
                    <strong style="font-size: 50px">
                        <a href="#" onclick="document.getElementById('save').submit()">
                            <span class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span>
                        </a>
                    </strong>
                </form>
            </td>
        </tr>
    </table>
@endsection
