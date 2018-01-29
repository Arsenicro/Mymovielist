@extends('layouts.app')

@section('content')
    <table>
        <tr>
            <td class="text-center" valign="top" width="20%">
                <strong style="text-align: center; font-size: 50px">
                    ID: {{ $info->id }} <a href="{{ route('person', [$info->id]) }}"><span class="glyphicon glyphicon-arrow-left"
                                                                                          style="margin-left: 10px"></span></a>
                    <a href="{{ route('deletePerson',[$info->id]) }}" onclick="return confirm('Are you sure?')"><span class="glyphicon glyphicon-erase"></span></a>
                </strong>

                <form class="text-center" action="{{ action('PersonController@save',[$info->id]) }}" id="save" method="post">
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
                                Image
                            </td>
                            <td>
                                <input type="hidden" name="oldimage" value="{{ $info->photo }}">
                                <textarea cols="50" rows="1" name="image">{{ $info->photo }}</textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Biography
                            </td>
                            <td>
                                <input type="hidden" name="oldbiography" value="{{ $info->biography }}">
                                <textarea cols="50" rows="5" name="biography">{{ $info->biography }}</textarea>
                            </td>
                        </tr>
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
