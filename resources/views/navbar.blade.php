@section('navbar')
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('movieList') }}">Movie List</a></li>
                    <li><a href="{{ route('userList') }}">User List</a></li>
                    <li><a href="{{ route('personList') }}">Person List</a></li>
                    <li><a href="{{ route('search') }}">Search</a></li>
                </ul>
                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                                <li>
                                    <a href="{{ route('user',[Auth::user()->login]) }}">
                                        Profile
                                    </a>
                                </li>
                                @if(Auth::user()->access == 'a' || Auth::user()->access == 'm')
                                    <li>
                                        <a href="{{ route('adding') }}">
                                            Adding
                                        </a>
                                    </li>
                                @endif
                                @if(Auth::user()->access == 'a')
                                    <li>
                                        <a href="{{ route('logs') }}">
                                            Logs
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                        @endguest
                </ul>
            </div>
        </div>
    </nav>
@endsection
