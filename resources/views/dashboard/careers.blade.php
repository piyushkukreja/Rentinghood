@extends('layouts.public')
@section('navbar')
    <div class="nav-container">
        <div class="bar bar--sm visible-xs bar--mobile-sticky box-shadow" data-scroll-class="59px:pos-fixed">
            <div class="container">
                <div class="row">
                    <div class="col-8 col-md-2">
                        <a href="{{ route('home') }}">
                            <h2 style="font-weight: 900; margin-bottom: 0; display: inline-block;">
                                rentinghood
                            </h2>
                        </a>
                    </div>
                    <div class="col-4 col-md-10 text-right">
                        <a href="#" class="hamburger-toggle" data-toggle-class="#menu1;hidden-xs">
                            <i class="icon icon--sm stack-interface stack-menu"></i>
                        </a>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </div>
        <!--end bar-->
        <nav id="menu1" class="bg--white bar bar--sm bar-1 hidden-xs box-shadow" data-scroll-class='64px:pos-fixed'>
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4 hidden-xs">
                        <div class="bar__module" style="margin-bottom: 0;">
                            <a href="{{ route('home') }}" >
                                <h3 style="font-weight: 900; margin-bottom: 0; color: #555555; display: inline;">
                                    rentinghood
                                </h3>
                            </a>
                        </div>
                        <!--end module-->
                    </div>
                    <div class="col-lg-9 col-md-12 text-right text-left-xs text-left-sm">
                        <div class="bar__module" style="margin-bottom: 0;">
                            <ul class="menu-horizontal text-left">
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('rent_categories') }}">Categories</a></li>
                                <li><a href="{{ route('lend_categories') }}">Lend</a></li>
                                <li><a href="{{ route('about_us') }}">About Us</a></li>
                                @auth
                                    <li><a href="{{ route('account') }}">Account</a></li>
                                @endauth
                                <li><a href="{{ route('contact') }}">Contact Us</a></li>
                            </ul>
                        </div>
                        <!--end module-->
                        <div class="bar__module">
                            @guest
                                <a class="btn btn--sm btn--primary type--uppercase" id="login_nav_button" href="{{ route('login') }}">
                                <span class="btn__text">
                                    Login
                                </span>
                                </a>
                                <a id="register_nav_button" class="btn btn--sm type--uppercase" id="register_nav_button" href="{{ route('register') }}">
                                <span class="btn__text">
                                    Sign Up
                                </span>
                                </a>
                            @else
                                <a class="btn btn--sm btn--primary type--uppercase" id="logout_nav_button" href="{{ route('login') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                <span class="btn__text">
                                    Logout
                                </span>
                                </a>

                                <form id="logout-form"   action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            @endguest
                        </div>
                        <!--end module-->
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </nav>
        <!--end bar-->
        @yield('location_field')
    </div>
@endsection
@section('head')
    @parent
    <style type="text/css">
        .nav-container, #menu1 {
            background: #fff;
        }
        #login_nav_button, #logout_nav_button {
            background: #34A9DE;
            border-color: #34A9DE;
        }
        #login_nav_button:hover, #logout_nav_button:hover {
            background: #43afe0;
        }
        #register_nav_button {
            background: #fff;
            border-color: #626262;
        }
        #register_nav_button .btn__text {
            color: #626262;
        }
        section.main-container-section {
            padding-top: 2em;
            padding-bottom: 2em;
        }
        .openings-heading-row.row {
            margin-top: 3em;
        }
        .feature img {
            height: 2.5em;
        }
        .feature p {
            font-size: 1.2em;
            margin-bottom: 0.5em;
        }

        .feature .apply-btn {
            color: #fff;
            background: #34A9DE;
            border-color: #34A9DE;
        }

        .feature .apply-btn:hover {
            border-color: #51b5e3;
            background: #51b5e3;
        }

        .feature .apply-btn .btn__text {
            color: #fff;
        }

        .openings-row .opening-container .feature {
            margin-bottom: 30px !important;
            height: 100%;
        }
        .row.openings-row {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            flex-wrap: wrap;
        }
        .row.openings-row > [class*='col-'] {
            display: flex;
            flex-direction: column;
        }
        @media (max-width: 767px) {
            section.main-container-section {
                padding-top: 0;
            }

            .feature img {
                height: 3em;
            }

            .heading-block {
                margin-bottom: 1em;
            }

            .openings-heading-row.row {
                margin-top: 4em;
            }

            .opening-container .feature {
                margin: 1em 2em !important;
            }
        }
        @media (min-width: 1024px) {
            @media (max-width: 1200px) {
                .feature img {
                    height: 2em;
                }
            }
        }
        #location_field, #swal_location_field {
            font-family: FontAwesome, "Open Sans", Verdana, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: inherit;
        }
        #scroll_to_content {
            position: absolute;
            width: 3.71428571em;
            height: 3.71428571em;
            background: #fff;
            left: 50%;
            margin-left: -1.85em;
            border-radius: 50%;
            text-align: center;
            bottom: 0;
            padding-top: 12px;
            box-shadow: 0 0 25px 0 rgba(0, 0, 0, 0.04);
            z-index: 98;
            border: 1px solid #ececec;
            transition: 0.2s ease-out;
            -webkit-transition: 0.2s ease-out;
            -moz-transition: 0.2s ease-out;
            display: block;
        }
        #scroll_to_content i {
            color: #252525;
        }
        #scroll_to_content:hover {
            transform: translate3d(0, -5px, 0);
            -webkit-transform: translate3d(0, -5px, 0);
        }
    </style>
@endsection
@section('content')
    <div class="main-container">
        <section class="bg--primary main-container-section" style="background: #34A9DE;">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-lg-5 col-md-7">
                        <div class="mt--2">
                            <h1>Careers</h1>
                            <p class="lead">
                                At RentingHood we believe every neighbour can contribute something to the community.
                                Come join the fastest growing Neighbourhood in town,
                                together let's build something awesome.
                            </p>
                            {{--<a class="btn btn--primary type--uppercase" href="https://goo.gl/forms/Ye7DvSNGqiFbJjP63">
                                <span class="btn__text">
                                    Apply
                                </span>
                            </a>--}}
                        </div>
                    </div>
                    <div class="col-lg-7 col-md-5 col-12">
                        <img alt="Image" src="{{ asset('img/careers/careersbg.gif') }}" />
                    </div>
                    <div class="col-md-12">
                        <div class="text-center">
                            <a id="scroll_to_content" href="#openings">
                                <i class="stack-interface stack-down-open-big"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <!--end of row-->
                <div class="row openings-heading-row">
                    <div class="col-md-12">
                        <hr>
                        <div class="heading-block">
                            <h3>Current Openings</h3><a id="openings"></a>
                        </div>
                    </div>
                </div>
                <div class="row openings-row">
                    <div class="col-12 col-md-6 col-lg-4 opening-container">
                        <div class="feature boxed boxed--border bg--white">
                            <div class="feature__body">
                                <div class="row align-content-center">
                                    <div class="col-12 col-md-5 text-center">
                                        <img class="icon" src="{{ asset('img/careers/graphic-designing1.png') }}">
                                    </div>
                                    <div class="col-12 col-md-7 text-center">
                                        <p>
                                            Graphic Designing Internship
                                        </p>
                                        <a class="btn apply-btn type--uppercase"
                                           href="https://docs.google.com/forms/d/e/1FAIpQLScEHNDyYGIEykKOGxi0bmoeOtWkO20tHjc4RR-MZS8DhYNt_w/viewform?usp=pp_url&entry.1994810835=Graphic+Designing&entry.1781500597&entry.1640447617&entry.297979220&entry.1541916407">
                                            <span class="btn__text">
                                                Apply
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end feature-->
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 opening-container">
                        <div class="feature boxed boxed--border bg--white">
                            <div class="feature__body">
                                <div class="row align-content-center">
                                    <div class="col-12 col-md-5 text-center">
                                        <img class="icon" src="{{ asset('img/careers/web-development1.png') }}">
                                    </div>
                                    <div class="col-12 col-md-7 text-center">
                                        <p>
                                            Web Development Internship
                                        </p>
                                        <a class="btn apply-btn type--uppercase"
                                           href="https://docs.google.com/forms/d/e/1FAIpQLScEHNDyYGIEykKOGxi0bmoeOtWkO20tHjc4RR-MZS8DhYNt_w/viewform?usp=pp_url&entry.1994810835=Web+Development&entry.1781500597&entry.1640447617&entry.297979220&entry.1541916407">
                                            <span class="btn__text">
                                                Apply
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end feature-->
                    </div>
                    <div class="col-12 col-md-6 col-lg-4 opening-container">
                        <div class="feature boxed boxed--border bg--white">
                            <div class="feature__body">
                                <div class="row align-content-center">
                                    <div class="col-12 col-md-5 text-center">
                                        <img class="icon" src="{{ asset('img/careers/business-development.png') }}">
                                    </div>
                                    <div class="col-12 col-md-7 text-center">
                                        <p>
                                            Business Development Internship
                                        </p>
                                        <a class="btn apply-btn type--uppercase"
                                           href="https://docs.google.com/forms/d/e/1FAIpQLScEHNDyYGIEykKOGxi0bmoeOtWkO20tHjc4RR-MZS8DhYNt_w/viewform?usp=pp_url&entry.1994810835=Business+Development&entry.1781500597&entry.1640447617&entry.297979220&entry.1541916407">
                                            <span class="btn__text">
                                                Apply
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end feature-->
                    </div>
                </div>
            </div>
            <!--end of container-->
        </section>
    </div>
    <script>
        $(document).ready(function () {

            $('#scroll_to_content').click(function (e) {
                e.preventDefault();
                scrollToItem($($(this).attr('href')));
            });

            function scrollToItem(item) {
                $('html, body').animate({
                    scrollTop: item.offset().top - 170
                }, 1000);
            }

        });
    </script>

@endsection