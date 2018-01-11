@section('searchbar')
    <form id="search" action="{{ action('SearchController@search') }}" method="get">
        <label for="search">Search:</label>
        <input class="form-control" rows="1" id="search" name="search" value="{{ $inputTitle }}">
        <div class="radio-inline">
            @if($movieList)
                <label><input checked type="radio" name="searching" value="movie">Movie</label>
            @else
                <label><input checked type="radio" name="searching" value="movie">Movie</label>
            @endif
        </div>
        <div class="checkbox-inline">
            @if($userList)
                <label><input checked type="radio" name="searching" value="user">User</label>
            @else
                <label><input type="radio" name="searching" value="user">User</label>
            @endif
        </div>
        <div class="checkbox-inline">
            @if($personList)
                <label><input checked type="radio" name="searching" value="people">People</label>
            @else
                <label><input type="radio" name="searching" value="people">People</label>
            @endif
        </div><br>
        <label for="genres">Genre:</label>
        <select class="form-control" id="genres" name="genres">
            <option>All</option>
            @foreach($genres as $genre)
                @if($genre == $inputGenre)
                    <option selected>{{ $genre }}</option>
                @endif
                <option>{{ $genre }}</option>
            @endforeach
        </select>
        @if($watched)
            <label class="checkbox-inline"><input checked type="checkbox" name="watched">Show watched</label><br>
        @else
            <label class="checkbox-inline"><input type="checkbox" name="watched">Show watched</label><br>
        @endif
        <button type="submit" class="btn btn-info" style="margin-top: 50px">Search</button>
    </form>
@endsection