<!DOCTYPE html>
<html>
    <head>
        <title>
            @yield('title', 'KDG Aggregator')
        </title>

        <meta charset='utf-8'>
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
        <link href="/css/styles.css" type='text/css' rel='stylesheet'>

        @stack('head')

    </head>
    <body class="{{ $bodyClass or 'noClass' }}">

        <nav class="navbar navbar-default navbar-static-top">
            <div class="container-fluid nav-container">
                <div class="navbar-header">

                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a href="/" id="websiteHeading" class="navbar-brand">KDG AGGREGATOR</a>

                </div>

                <div class="collapse navbar-collapse" id="navbar-collapse">

                    <ul class="nav navbar-nav navbar-left">

                        <li>
                            <a href="/work-done" id="workDoneMenuItem"><i class="far fa-clock"></i><span>Work Done</span></a>
                        </li>
                        <li>
                            <a href="/projects"><i class="fas fa-archive"></i><span>Projects</span></a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        @if(Auth::check())
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" id="menuNameAndPic" data-toggle="dropdown" role="button" aria-expanded="false" >
                                    <i class="fas fa-user"></i>
                                    <span id="userName">{{ Auth::user()->name }}</span> <span class="caret caretUserName"></span>
                                    <img src="/uploads/profile-pics/kdg_logo.jpg" id="menuProfilePic" alt="user photo"/>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href='{{ url("auth/logout") }}'
                                            onclick="
                                                     document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
                                        </a>

                                    </li>
                                    <li><a href="/settings"><i class="fas fa-cog"></i><span>Settings</span></a></li>
                                </ul>
                            </li>
                        @else
                            <li><a href='/login'><i class="fas fa-sign-in-alt"></i><span>Login</span></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="/js/teproject.js"></script>

        @stack('body')

    </body>
</html>
