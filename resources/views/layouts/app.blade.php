<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sweetwater API Testing Results Dashboard') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="https://media.sweetwater.com/api/i/f-webp__ha-3aadaaee8bc333dc__hmac-cb9d37a688d682d0c26e077b4b7a29f5485446a8/app-icons/favicon-16x16.png.auto.webp" sizes="16x16">
</head>
<body style="background-image: url({{ asset('/images/patern.png') }}); background-position: center; background-repeat: repeat;">
    <div id="app">
        <main class="py-4">            
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h3>
                                            <a href='{{ route('dashboard.view') }}'>
                                                <img src="{{ asset('images/apitest.png') }}" width="140" height="40" />                                             
                                            </a>
                                            @if (isset($name))
                                                <!-- nothing -->
                                            @endif
                                        </h3>                                                             
                                    </div>
                                    <div class="col-6">
                                        @auth
                                            <span class="float-right">
                                                <a class="nav-link text-white small" href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                                document.getElementById('logout-form').submit();">
                                           
                                                <img class="employee-photo large" src="{{ asset( 'images/admin.png' ) }}">

                                                </a>

                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </span>
                                        @endauth
                                    </div>
                                </div>   
                            </div>
                            <div class="card-body">
                                <br />
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="site_footer">
                © 2021 Sweetwater Sound Inc. All rights reserved. 
                @auth
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('footer-logout-form').submit();">Admin (Logout).</a>

                    <form id="footer-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}">Admin Login.</a>
                @endauth
            </footer> 
        </main>
    </div>
</body>

@routes
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>

</html>
