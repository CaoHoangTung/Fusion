<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="/js/app.js" defer></script>
    <script src="/js/jquery-3.1.0.min.js"></script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet" defer>
    <link href="/css/customlayout.css" rel="stylesheet" defer>
</head>
<body>
    <div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel" style="height: 100px;">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="/images/icon.png">
                    <span style="font-size: 23px; color: #3C75AF">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto main-menu">
                        <!-- Authentication Links -->
                        <li class="nav-item">
                                <a class="nav-link" href="/home">Home</a>    
                            </li> 
                            <li class="nav-item dropdown">   
                                <a href="/contests" id="navbarDropdown" class="nav-link">
                                    Contests <span class="caret"></span>
                                </a>
                            </li>
                            <li class="nav-item dropdown">   
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Learn <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="/learn/lectures">
                                        Lectures
                                    </a>
                                    <a class="dropdown-item" href="/learn/archive">
                                        Archived problems
                                    </a>
                                </div>
                            </li>                            
                            <li class="nav-item">     
                                <a class="nav-link" href="/ranking">Ranking</a>    
                            </li>                              
                            <li class="nav-item">
                                <a class="nav-link" href="/profile" style="font-family: ">Profile</a>    
                            </li>
                            <span style="margin-right:5vw"></span>
                            <li>
                                <div class="input-group mb-3 searchbox">
                                    <form method="get" action="/search" style="width:100%">
                                        <input type="text" class="form-control" name="query" placeholder="Search">
                                    </form>
                                </div>
                            </li>
                            <span style="margin-right:1vw"></span>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                @endif
                            </li>
                        @else   
                            <li class="nav-item dropdown">   
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    @if (Auth::user()->Role === 'admin')
                                    <a class="dropdown-item" href="/sadmin/dashboard">
                                        Admin dashboard
                                    </a>
                                    @endif
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <div class="profile">

        </div>

        <main class="py-4">
        <div class="container">
            <div class="row justify-content-left">

                <div class="col-md-9">
                    <div class="card">
                        @yield('content')
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header card-title">Upcoming contests</div>

                        <div class="card-body">
                            <div class="card-upcomingcontest">
                                
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header card-title">Profile</div>

                        <div class="card-body">
                            <div class="card-profile">
                                @guest
                                    Please <a href="/login">login</a> first
                                @else
                                    <div class="avatar">
                                        <img alt="" src="{{$profile->Avatar}}">
                                    </div>
                                    <div class="info">
                                        <div class="title">
                                            <a target="_blank" href="/profile/{{$profile->id}}">{{$profile->name}}</a>
                                        </div>
                                        <div class="desc">{{$profile->Rating}}</div>
                                    </div>
                                    <div class="bottom">
                                        
                                    </div>
                                @endguest
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header card-title">Leaderboard</div>

                        <div class="card-body">
                            <div class="card-topusers">

                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        </main>
    </div>
</body>

<script src="/js/getCustomData.js"></script>
<script>
    getUpcomingContests();
    getTopUsers();
</script>
@yield('scripts');

</html>
