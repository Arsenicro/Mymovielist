@extends('layouts.app')

@section('content')
   <div class="text-center">
       <strong style="font-size: 50px">Add movie:</strong>
       <form method="post" action="{{ route('addingMovie') }}" id="addMovie">
           {{ csrf_field() }}
           <label for="movieTitle">Movie title:</label>
           <input type="text" class="form-control" name="movieTitle" id="movieTitle">
           <button type="submit" class="btn btn-default">Save</button>
       </form>
   </div>
   <div class="text-center">
       <strong style="font-size: 50px">Add person:</strong>
       <form method="post" action="{{ route('addingPerson') }}" id="addPerson">
           {{ csrf_field() }}
           <label for="personName">Person name:</label>
           <input type="text" class="form-control" name="personName" id="personName">
           <label for="personSurname">Person surname:</label>
           <input type="text" class="form-control" name="personSurname" id="personSurname">
           <button type="submit" class="btn btn-default">Save</button>
       </form>
   </div>
   <div class="text-center">
       <strong style="font-size: 50px">Add genre:</strong>
       <form method="post" action="{{ route('addingGenre') }}" id="addGenre">
           {{ csrf_field() }}
           <label for="genreName">Genre name:</label>
           <input type="text" class="form-control" name="genreName" id="genreName">
           <button type="submit" class="btn btn-default">Save</button>
       </form>
   </div>
   <div class="text-center">
       <strong style="font-size: 50px">Delete genre:</strong>
       <form method="post" action="{{ route('deleteGenre') }}" id="deleteGenre">
           {{ csrf_field() }}
           <label for="genreName">Genre name:</label>
           <input type="text" class="form-control" name="genreName" id="genreName">
           <button type="submit" class="btn btn-default" onclick="return confirm('Are you sure?')">Delete</button>
       </form>
   </div>
@endsection
