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
        </tr>
    </table>
@endsection