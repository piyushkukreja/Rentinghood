@extends('layouts.app')
@section('navbar')
    <style>
        .hiring_div {
            position: fixed;
            width: 6em;
            bottom: 0;
            right: 0;
            z-index: 10;
            cursor: pointer;
            transition: transform 0.3s ease-out;
        }
        .hiring_div:hover {
            transform: translate(0,-5px);
        }
        .back-to-top {
            bottom: 11em;
        }
        @media (min-width: 1024px) {
            .bar-2 .bar__module + .bar__module {
                margin-left: 1.85714286em;
            }
            .hiring_div {
                position: fixed;
                width: 10em;
                bottom: 0;
                right: 0;
                z-index: 10;
            }
        }
        .bar-2 .menu-horizontal > li > a {
            letter-spacing: 0.5px;
        }
        h1.about_heading, h2.about_heading {
            font-weight: 600;
        }

        .menu-horizontal li:hover {
            transform: translate(0, -5px);
        }

        .menu-horizontal li {
            transition: transform .3s ease-out;
        }

        .logo-dark, .logo-light {
            font-weight: 900; margin-bottom: 0;
        }
    </style>
    <div class="nav-container">
        <div class="bar bar--sm visible-xs">
            <div class="container">
                <div class="row">
                    <div class="col-8 col-md-2">
                        <a href="{{ route('home') }}">
                            <h2 style="font-weight: 900; margin-bottom: 0; color: #333333; display: inline-block;">
                                rentinghood
                            </h2>
                        </a>
                    </div>
                    <div class="col-4 col-md-10 text-right">
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
                                <li><a href="{{ route('contact') }}">Contact Us</a></li>
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
        <div class="hiring_div"><img src="{{ asset('img/hiring2.png') }}"></div>
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
                    <div class="col-md-5">
                        <img alt="Image" class="rounded-circle" src="{{ asset('img/aboutus/avatar-1.JPG') }}" />
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="switchable__text">
                            <div class="text-block">
                                <h2>Bhushan Punjabi</h2>
                                <span>Founder &amp; CEO</span>
                            </div>
                            <p class="lead">
                                Prior to Rentinghood, founder Bhushan Punjabi spent his days working as a distribution partner for a multinational company ‘XS’ and built a team of over 50 individuals and successfully launched the XS energy drink in the ever flourishing Indian Market. He had also been a part of Uber. But even when his days were busy, his ideas and cups of coffee kept him awake at night. And in this journey of being a nocturnal, Rentinghood took its foundation.
                            </p>
                            <ul class="social-list list-inline list--hover">
                                <li>
                                    <a href="mailto:punjabibhushan@gmail.com">
                                        <i class="socicon socicon-mail icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.linkedin.com/in/punjabibhushan/">
                                        <i class="socicon socicon-linkedin icon icon--xs"></i>
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
                    <div class="col-md-5">
                        <img alt="Image" class="rounded-circle" src="{{ asset('img/aboutus/avatar-2.jpg') }}" />
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="switchable__text">
                            <div class="text-block">
                                <h2>Bharti Udasi</h2>
                                <span>Chief Creative Officer</span>
                            </div>
                            <p class="lead">
                                A graduate in Mass Media, Bharti is a very versatile person. When she isn’t working, she is usually found writing poems and performing them in poetry slams across Mumbai. She also does Graphology and is a part time Makeup Artist. With her varied interests, this feminist lady defines 'travelling' in books she reads, people she comes across and in her own journey of her life.
                            </p>
                            <ul class="social-list list-inline list--hover">
                                <li>
                                    <a href="mailto:bhartiudasi06@gmail.com">
                                        <i class="socicon socicon-mail icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.linkedin.com/in/bharti-udasi-45a199148/">
                                        <i class="socicon socicon-linkedin icon icon--xs"></i>
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
                    <div class="col-md-5">
                        <img alt="Image" class="rounded-circle" src="{{ asset('img/aboutus/avatar-3.jpeg') }}" />
                    </div>
                    <div class="col-md-8 col-lg-5">
                        <div class="switchable__text">
                            <div class="text-block">
                                <h2>Pankaj Ajwani</h2>
                                <span>Chief Technical Officer</span>
                            </div>
                            <p class="lead">
                                Being an undergrad hasn't stopped Pankaj from taking on multidisciplinary projects, which showcase his exemplary work. Having skills which span through the dimensions of website and application development, he is self-disciplined in multiple programming languages.
                            </p>
                            <ul class="social-list list-inline list--hover">
                                <li>
                                    <a target="_blank" href="mailto:pankaj.ajwani0409@gmail.com">
                                        <i class="socicon socicon-mail icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.linkedin.com/in/pankaj-ajwani-0409/">
                                        <i class="socicon socicon-linkedin icon icon--xs"></i>
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