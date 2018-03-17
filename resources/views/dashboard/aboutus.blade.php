@extends('layouts.app')
@section('navbar')
    <style>
        @media (min-width: 1024px) {
            .bar-2 .bar__module + .bar__module {
                margin-left: 1.85714286em;
            }
        }

        .bar-2 .menu-horizontal > li > a {
            letter-spacing: 0.5px;
        }
        h1.about_heading, h2.about_heading {
            font-weight: 600;
        }

        .logo-dark, .logo-light {
            font-weight: 900; margin-bottom: 0;
        }
    </style>
    <div class="nav-container">
        <div class="bar bar--sm visible-xs">
            <div class="container">
                <div class="row">
                    <div class="col-3 col-md-2">
                        <a href="{{ route('home') }}">
                            <h2 style="font-weight: 900; margin-bottom: 0; color: #333333;">
                                rentinghood
                            </h2>
                        </a>
                    </div>
                    <div class="col-9 col-md-10 text-right">
                        <a href="#" class="hamburger-toggle" data-toggle-class="#menu2;hidden-xs">
                            <i class="icon icon--sm stack-interface stack-menu"></i>
                        </a>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </div>
        <!--end bar-->
        <nav id="menu2" class="bar bar--sm bar-2 hidden-xs bar--transparent bar--absolute" data-scroll-class='90vh:pos-fixed'>
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4 hidden-xs">
                        <div class="bar__module" style="margin-bottom: 0;">
                            <a href="{{ route('home') }}">
                                <h3 class="logo-light" style="color: white;">
                                    rentinghood
                                </h3>
                                <h3 class="logo-dark" style="color: #555555 ;">
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
                                @auth
                                    <li><a href="{{ route('account') }}">Account</a></li>
                                @endauth
                            </ul>
                        </div>
                        <!--end module-->
                        <div class="bar__module">
                            @guest
                                <a class="btn btn--sm btn--primary type--uppercase" href="{{ route('login') }}">
                                <span class="btn__text">
                                    Login
                                </span>
                                </a>
                                <a class="btn btn--sm type--uppercase" href="{{ route('register') }}">
                                <span class="btn__text">
                                    Sign Up
                                </span>
                                </a>
                            @else
                                <a class="btn btn--sm btn--primary type--uppercase" href="{{ route('login') }}"
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
    </div>
@endsection
@section('content')
    <div class="main-container">
        <section class="text-center imagebg space--lg" data-overlay="6">
            <div class="background-image-holder">
                <img alt="background" src="{{ asset('img/aboutus/home3.jpg') }}" />
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-lg-6">
                        <h1 class="about_heading">Hi, We're RentingHood</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="text-center bg--primary" style="padding-bottom: 1em; padding-top: 2em; box-shadow: 0 23px 40px rgba(0, 0, 0, 0.2);">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                        <h2 class="about_heading">Who we are?</h2>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="text-center" style="padding-top: 4em; padding-bottom: 4em;">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                        <p class="lead">
                            We are a bunch of neighbours, who believe happiness can not only be bought but could also be rented, especially when you need it only for the time being.<br/><br>
                            We are trying to convert the neighbours into renters by giving them a digital friendly background that felicitates renting atmosphere and ensures sharing of the products with ease and security.<br/><br/>
                            Pro Tip – You earn money out of it!
                        </p>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        {{--<section class="text-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-11">
                        <div class="slider border--round" data-arrows="true" data-paging="true">
                            <ul class="slides">
                                <li>
                                    <img alt="Image" src="img/gallery-1.jpg" />
                                </li>
                                <li>
                                    <img alt="Image" src="img/cowork-1.jpg" />
                                </li>
                                <li>
                                    <img alt="Image" src="img/cowork-5.jpg" />
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>--}}
        <section class="text-center bg--primary" style="padding-bottom: 1em; padding-top: 2em; box-shadow: 0 23px 40px 0 rgba(0, 0, 0, 0.2);">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                        <h2 class="about_heading">Founding neighbours</h2>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="switchable feature-large" style="padding-top: 5em;">
            <div class="container">
                <div class="row justify-content-around">
                    <div class="col-md-6">
                        <img alt="Image" class="border--round" src="{{ asset('img/aboutus/avatar-wide-3.jpg') }}" />
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="switchable__text">
                            <div class="text-block">
                                <h2>Bhushan Punjabi</h2>
                                <span>Co-Founder &amp; Designer</span>
                            </div>
                            <p class="lead">
                                Launching an attractive and scalable website quickly and affordably is important for modern startups &mdash; Stack offers massive value without looking 'bargain-bin'.
                            </p>
                            <ul class="social-list list-inline list--hover">
                                <li>
                                    <a href="#">
                                        <i class="socicon socicon-mail icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="socicon socicon-twitter icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="socicon socicon-instagram icon icon--xs"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="switchable switchable--switch feature-large">
            <div class="container">
                <div class="row justify-content-around">
                    <div class="col-md-6">
                        <img alt="Image" class="border--round" src="{{ asset('img/aboutus/avatar-wide-4.jpg') }}" />
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="switchable__text">
                            <div class="text-block">
                                <h2>Bharti Udasi</h2>
                                <span>Co-Founder &amp; Developer</span>
                            </div>
                            <p class="lead">
                                Launching an attractive and scalable website quickly and affordably is important for modern startups &mdash; Stack offers massive value without looking 'bargain-bin'.
                            </p>
                            <ul class="social-list list-inline list--hover">
                                <li>
                                    <a href="#">
                                        <i class="socicon socicon-mail icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="socicon socicon-twitter icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="socicon socicon-instagram icon icon--xs"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="switchable feature-large">
            <div class="container">
                <div class="row justify-content-around">
                    <div class="col-md-6">
                        <img alt="Image" class="border--round" src="{{ asset('img/aboutus/avatar-wide-1.jpg') }}" />
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="switchable__text">
                            <div class="text-block">
                                <h2>Pankaj Ajwani</h2>
                                <span>Front-End Developer</span>
                            </div>
                            <p class="lead">
                                Launching an attractive and scalable website quickly and affordably is important for modern startups &mdash; Stack offers massive value without looking 'bargain-bin'.
                            </p>
                            <ul class="social-list list-inline list--hover">
                                <li>
                                    <a href="#">
                                        <i class="socicon socicon-mail icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="socicon socicon-google icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="socicon socicon-twitter icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="socicon socicon-instagram icon icon--xs"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
    </div>
@endsection