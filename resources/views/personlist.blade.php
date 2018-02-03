@section('list')
        <table class="table table-striped" width="100%">
            <tr>
                <th width="45%" style="text-align: center">
                    Name
                    <a href="?sortby=name&order=asc{{ $get }}">
                        <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                    </a>
                    <a href="?sortby=name&order=desc{{ $get }}">
                        <span class="glyphicon glyphicon-sort-by-order"></span>
                    </a>
                </th>
                <th width="45%" style="text-align: center">
                    Surname
                    <a href="?sortby=surname&order=asc{{ $get }}">
                        <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                    </a>
                    <a href="?sortby=surname&order=desc{{ $get }}">
                        <span class="glyphicon glyphicon-sort-by-order"></span>
                    </a>
                </th>
                <th width="10%" style="text-align: center">
                    Number of Fans
                    <a href="?sortby=fans&order=asc{{ $get }}">
                        <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                    </a>
                    <a href="?sortby=fans&order=desc{{ $get }}">
                        <span class="glyphicon glyphicon-sort-by-order"></span>
                    </a>
                </th>
            </tr>
            @foreach($persons as $person)
                <tr>
                    <th>
                        <img src="{{ $person->photo }}" width="100px" height="150px" style="float: left; margin-right: 20px">
                        <a href="{{ route('person',[$person->id]) }}">{{ $person->name }}</a>
                    </th>
                    <th style="text-align: center">
                        <a href="{{ route('person',[$person->id]) }}">{{ $person->surname }}</a>
                    </th>
                    <th style="text-align: center">
                        {{ $person->fans }}
                    </th>
                </tr>
            @endforeach
        </table>
@endsection
