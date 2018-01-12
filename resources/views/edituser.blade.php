@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td width="10%" valign="top">
                <div style="float: left; margin-right: 20px; text-align: center">
                    <img src="{{ $info->avatar }}" width="200px" height="300px"><br>
                    <form action="{{ action('UserController@saveAvatar',[$info->login]) }}" id="avatarSubmit" method="post" style="margin-top: 10px">
                        {{ csrf_field() }}
                        <textarea class="text-center form-control" rows="5" name="avatar">{{ $info->avatar }}</textarea><a
                                href="#" onclick="document.getElementById('avatarSubmit').submit()"><span
                                    class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                    </form>
                    <div style="text-align: center">
                        <form action="{{ action('UserController@saveName',[$info->login]) }}" id="nameSubmit" method="post">
                            {{ csrf_field() }}
                            <label>Name:</label>
                            <textarea class="text-center form-control" rows="1" name="name">{{ $info->name }}</textarea><a
                                    href="#" onclick="document.getElementById('nameSubmit').submit()"><span
                                        class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                        </form>
                    </div>
                    <div style="text-align: center">
                        <form action="{{ action('UserController@saveSurname',[$info->login]) }}" id="surnameSubmit" method="post">
                            {{ csrf_field() }}
                            <label for="surname">Surname:</label>
                            <textarea class="text-center form-control" rows="1" name="surname">{{ $info->surname }}</textarea><a
                                    href="#" onclick="document.getElementById('surnameSubmit').submit()"><span
                                        class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                        </form>
                    </div>
                    <div style="text-align: center">
                        <form action="{{ action('UserController@saveLocation',[$info->login]) }}" id="saveLocation" method="post">
                            {{ csrf_field() }}
                            <label for="surname">Location:</label>
                            <textarea class="text-center form-control" rows="1" name="location">{{ $info->location }}</textarea><a
                                    href="#" onclick="document.getElementById('saveLocation').submit()"><span
                                        class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                        </form>
                    </div>
                    <div style="text-align: center">
                        <form action="{{ action('UserController@saveBirthday',[$info->login]) }}" id="saveBirthday" method="post">
                            {{ csrf_field() }}
                            <label for="surname">Birthday (YYYY-MM-DD):</label>
                            <textarea class="text-center form-control" rows="1" name="birthday">{{ $info->birthday }}</textarea><a
                                    href="#" onclick="document.getElementById('saveBirthday').submit()"><span
                                        class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                        </form>
                    </div>
                </div>
            </td>
            <td class="text-center" valign="top" width="90%">
                <strong style="font-size: 30px; text-align: center;">
                    {{ $info->login }}  <a href="{{ route('user',[$info->login]) }}"><span class="glyphicon glyphicon-ok"
                                                                                         style="margin-left: 10px"></span></a>
                    <a href="{{ route('deleteUser',[$info->login]) }}" onclick="return confirm('Are you sure?')"><span class="glyphicon glyphicon-erase" style="margin-left: 10px"></span></a>
                    <br>
                </strong>
                <form action="{{ action('UserController@saveGender',[$info->login]) }}" id="saveGender" method="post">
                    {{ csrf_field() }}
                    <label for="surname">Gender:</label>
                    <select class="form-control" name="gender">
                        @if($info->gender == "male")
                            <option selected>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        @elseif($info->gender == "female")
                            <option>Male</option>
                            <option selected>Female</option>
                            <option>Other</option>
                        @else
                            <option>Male</option>
                            <option>Female</option>
                            <option selected>Other</option>
                        @endif
                    </select>
                    <a href="#" onclick="document.getElementById('saveGender').submit()"><span
                                class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                </form>
                @if($isAdmin)
                    <form action="{{ action('UserController@saveAccess',[$info->login]) }}" id="saveAccess" method="post">
                        {{ csrf_field() }}
                        <label for="surname">Access:</label>
                        <select class="form-control" name="access">
                            @if($info->access == "a")
                                <option selected>Admin</option>
                                <option>Moderator</option>
                                <option>User</option>
                            @elseif($info->gender == "m")
                                <option>Admin</option>
                                <option selected>Moderator</option>
                                <option>User</option>
                            @else
                                <option>Admin</option>
                                <option>Moderator</option>
                                <option selected>User</option>
                            @endif
                        </select>
                        <a href="#" onclick="document.getElementById('saveAccess').submit()"><span
                                    class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                    </form>
                @endif
                <div style="margin-top: 50px">
                    <form action="{{ action('UserController@saveAbout',[$info->login]) }}" id="saveAbout" method="post">
                        {{ csrf_field() }}
                        <label for="surname">About:</label>
                        <textarea class="text-center form-control" rows="10" name="about">{{ $info->about }}</textarea><a
                                href="#" onclick="document.getElementById('saveAbout').submit()"><span
                                    class="glyphicon glyphicon-floppy-disk" style="margin-left: 10px"></span></a>
                    </form>
                </div>
            </td>
        </tr>
    </table>
@endsection
