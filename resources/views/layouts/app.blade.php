<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Site Description Here">
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    {{--<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">--}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/stack-interface.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/socicon.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/iconsmind.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/flickity.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" media="all"/>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:200,300,400,400i,500,600,700%7CMerriweather:300,300i"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    {{--<script src="http://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>--}}
    <script src="{{ asset('js/tether.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
</head>
<body class=" " data-smooth-scroll-offset='64'>
<style>
    @media (min-width: 768px) {
        footer.footer-3 .row:last-child {
            margin-top: 0.5em;
        }

    }
    @media (max-width: 767px) {
        .btn:not(:last-child) {
            margin-bottom: 0;
        }
    }
    .modal-container .modal-content {

        border-radius: 12px;

    }
    .menu-horizontal > li:not(:hover) > a {
        opacity: 0.8;
    }

    .nav-container {
        font-size: 1.15em  ;
    }
</style>
<a id="start"></a>

@yield('navbar')

@yield('content')

<footer class="footer-3 text-center-xs space--xs bg--dark ">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h3 style="font-weight: 900; margin-bottom: 0; color: #5f5f5f; display: inline-block;">
                    rentinghood
                </h3>
                {{--<ul class="list-inline list--hover">
                    <li class="list-inline-item">
                        <a href="#">
                            <span class="type--fine-print">Get Started</span>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="#">
                            <span class="type--fine-print">help@stack.io</span>
                        </a>
                    </li>
                </ul>--}}
            </div>
            <div class="col-md-6 text-right text-center-xs">
                <ul class="social-list list-inline list--hover">
                    <li class="list-inline-item">
                        <a href="https://www.linkedin.com/company/rentinghood">
                            <i class="socicon socicon-linkedin icon icon--xs"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://www.facebook.com/rentinghood">
                            <i class="socicon socicon-facebook icon icon--xs"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a href="https://www.linkedin.com/company/rentinghood">
                            <i class="socicon socicon-instagram icon icon--xs"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!--end of row-->
        <div class="row">
            <div class="col-md-6">
                <p class="type--fine-print">
                    Rent anything, right from your neighbourhood
                </p>
            </div>
            <div class="col-md-6 text-right text-center-xs">
                            <span class="type--fine-print">&copy;
                                <span class="update-year"></span> RentingHood Inc.</span>
            </div>
        </div>
        <!--end of row-->
    </div>
    <!--end of container-->
</footer>
</div>
<!--<div class="loader"></div>-->
<a class="back-to-top inner-link" href="#start" data-scroll-class="100vh:active">
    <i class="stack-interface stack-up-open-big"></i>
</a>

<script src="{{ asset('js/typed.min.js') }}"></script>
<script src="{{ asset('js/isotope.min.js') }}"></script>
<script src="{{ asset('js/granim.min.js') }}"></script>
<script src="{{ asset('js/smooth-scroll.min.js') }}"></script>
<script src="{{ asset('js/flickity.min.js') }}"></script>
<script src="{{ asset('js/scripts.js') }}"></script>

</body>

</html>
