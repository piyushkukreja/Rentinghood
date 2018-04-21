<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rent anything, right from your neighbourhood">
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}"/>
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
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <script src="{{ asset('js/tether.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    {{--Places API and Geocomplete plugin --}}
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDtfAuKKrycjdbscKGGfbCg0R5udw3N73g&amp;libraries=places"></script>
    <script src="{{ asset('js/jquery.geocomplete.min.js') }}"></script>
    <style>
        body::-webkit-scrollbar {display:none;}
        ol.breadcrumbs, ol.breadcrumbs > li {
            margin-bottom: 0;
            font-size: 1.08em;
        }
        .menu-horizontal > li:not(:hover) > a {
            opacity: 0.8;
        }
        .nav-container {
            font-size: 1.15em;
        }
        .title_section {
            padding-top: 1.5em;
            padding-bottom: 1.5em;
        }
        .title_section h1 {
            margin-bottom: 0.1em;
        }
        section.categories_section.space--sm {
            padding-top: 2em;
        }
        .modal-container .modal-content {
            max-width: 90vw;
            border-radius: 12px;
        }
        .modal-container .modal-content .container {
            max-width: 100%;
        }
        #pre-loader {
            background-color: #34A9DE;
            height: 100%;
            width: 100%;
            position: fixed;
            margin-top: 0;
            top: 0;
            left: 0;
            bottom: 0;
            overflow: hidden !important;
            right: 0;
            z-index: 100;
        }
        #pre-loader img {
            text-align: center;
            left: 0;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            -webkit-transform: translateY(-50%);
            -o-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            z-index: 99;
            margin: 0 auto;
        }
        @media (min-width: 576px) {
        }
        @media (min-width: 768px) {
            footer.footer-3 .row:last-child {
                margin-top: 0.5em;
            }

            footer .row > :last-child > :last-child {
                padding-top: 0.75em;
            }

            #otp_modal .modal-content, #login_modal .modal-content, #new_message_modal .modal-content {
                max-width: 500px;
            }

            .modal-container .modal-content {
                max-width: 80vw;
            }
        }
        @media (min-width: 992px) {
            .modal-container .modal-content {
                max-width: 70vw;
            }
        }
        @media (min-width: 1200px) {
        }
        @media (max-width: 575px) {
            #new_message_modal .modal-content, #location_modal .modal-content {
                width: 90vw;
                padding-top: 0.5em;
                padding-bottom: 0;
            }

            #new_message_modal .modal-content .modal-close-cross, #location_modal .modal-content .modal-close-cross {
                top: 2em;
            }
        }
        @media (max-width: 767px) {
            .btn:not(:last-child) {
                margin-bottom: 0;
            }
        }
    </style>
</head>
<body class="" data-smooth-scroll-offset="64">
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
                <p class="type--fine-print">
                    Rent anything, right from your neighbourhood
                </p>
                <a class="type--fine-print" style="margin-left: 0;" href="{{ route('contact') }}">
                    Have something to say?
                </a>
            </div>
            <div class="col-md-6">
                <div class="text-right text-center-xs">
                    <ul class="social-list list-inline list--hover">
                        <li class="list-inline-item">
                            <a target="_blank" href="https://www.linkedin.com/company/rentinghood">
                                <i class="socicon socicon-linkedin icon icon--xs"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a target="_blank" href="https://www.facebook.com/rentinghood">
                                <i class="socicon socicon-facebook icon icon--xs"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a target="_blank" href="https://www.instagram.com/rentinghood">
                                <i class="socicon socicon-instagram icon icon--xs"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="text-right text-center-xs">
                    <span class="type--fine-print">&copy;<span class="update-year"></span> RentingHood Inc.</span>
                </div>
            </div>
        </div>
    </div>
</footer>
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
<script>
    $('.hiring_div').on('click', function () {
        window.location.href = '{{ route('careers') }}';
    })
</script>

</body>

</html>
