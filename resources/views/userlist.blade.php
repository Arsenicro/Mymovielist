@section('list')
        <table class="table table-striped" width="100%">
            <tr>
                <th>
                    Login
                    <a href="?sortby=name&order=asc{{ $get }}">
                        <span class="glyphicon glyphicon-sort-by-order-alt"></span>
                    </a>
                    <a href="?sortby=name&order=desc{{ $get }}">
                        <span class="glyphicon glyphicon-sort-by-order"></span>
                    </a>
                </th>
            </tr>
            @foreach($users as $user)
                <tr>
                    <th>
                        <img src="{{ $user->avatar }}" width="120px" height="120px" style="float: left; margin-right: 20px">
                        <a href="{{ route('user',[$user->login]) }}">{{ $user->login }}</a>
                    </th>
                </tr>
            @endforeach
        </table>
@endsection
