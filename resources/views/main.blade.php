<!DOCTYPE html>
<html lang="fr-FR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="https://eprel.u-pec.fr/home/images/logo_eprel_v2_rouge.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/45e38e596f.js" crossorigin="anonymous"></script>
</head>
<body>
<header class="mt-3 nav-tabs">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start ">

            <nav class="col-12 mb-2 justify-content-center mb-md-0">
                <div class="col col-lg-auto me-lg-auto nav" id="nav-tab" role="tablist">
                    @yield('nav')


                    @guest()
                        <div class="mx-auto mb-3" style="margin-right: 2%!important;">
                            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-success">Sign-up</a>
                        </div>
                    @endguest
                    @auth()
                        <div class="mx-auto mb-3" style="margin-right: 2%!important;">
                            <a href="{{ route('logout') }}" class="btn btn-primary">Logout</a>
                            @if(Auth::user()->type != "admin")
                                <a href="{{ route('profile') }}" class="btn btn-success">Profile</a>
                            @endif
                        </div>
                    @endauth
                </div>
            </nav>
        </div>
    </div>
</header>
@yield('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
@yield('scripts')
</body>
</html>
