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
        .menu-horizontal li:hover {
            transform: translate(0, -5px);
        }
        .menu-horizontal li {
            transition: transform .3s ease-out;
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
            bottom: 2.71428571em;
            padding-top: 12px;
            box-shadow: 0 0 25px 0 rgba(0, 0, 0, 0.04);
            z-index: 98;
            border: 1px solid #ececec;
            transition: 0.2s ease-out;
            -webkit-transition: 0.2s ease-out;
            -moz-transition: 0.2s ease-out;
            display: none;
        }
        #scroll_to_content i {
            color: #252525;
        }
        #scroll_to_content:hover {
            transform: translate3d(0, -5px, 0);
            -webkit-transform: translate3d(0, -5px, 0);
        }
        @media (max-width: 767px) {
            #home {
                padding: 12em 0;
                min-height: calc(100vh - 5em);
            }
            footer .social-list {
                margin: 1.5em 0;
            }
        }
        #scroll_to_content {
            display: block;
        }
        @media (min-width: 768px) {
            #home {
                min-height: 100vh;
            }
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
        <nav id="menu2" class="bar bar--sm bar-2 hidden-xs bar--transparent bar--absolute"
             data-scroll-class='90vh:pos-fixed'>
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4 hidden-xs">
                        <div class="bar__module" style="margin-bottom: 0;">
                            <a href="{{ route('home') }}">
                                <h3 class="logo-light" style="font-weight: 900; margin-bottom: 0; color: white;">
                                    rentinghood
                                </h3>
                                <h3 class="logo-dark" style="font-weight: 900; margin-bottom: 0; color: #555555 ;">
                                    rentinghood
                                </h3>
                            </a>
                        </div>
                        <!--end module-->
                    </div>
                    <div class="col-lg-9 col-md-12 text-right text-left-xs text-left-sm">
                        <div class="bar__module" style="margin-bottom: 0;">
                            <ul class="menu-horizontal text-left">
                                <li><a href="{{ route('about_us') }}">About Us</a></li>
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

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
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
    @auth
        <div id="messages_modal_template" class="hidden">
            <div class="modal-instance">
                <div id="new_message_modal" class="modal-container">
                    <div class="modal-content">
                        <div class="boxed boxed--lg">
                            <h2>You have new messages</h2>
                            <hr class="short">
                            <p class="lead">
                                Visit the messages tabs to view requests for your products and to answer them.
                            </p>
                            <div class="text-center">
                                <a class="btn btn--lg btn--primary type--uppercase"
                                   href="{{ route('account', ['messages']) }}">
                                    <span class="btn__text">View messages</span>
                                </a>
                            </div>
                        </div>
                        <div class="modal-close modal-close-cross"></div>
                    </div>
                </div>
                <a href="#" class="modal-trigger hidden"></a>
            </div>
        </div>
    @endauth
    <div class="main-container">
        <section class="cover height-90 imagebg text-center" data-overlay="5" id="home">
            <div class="background-image-holder">
                <img alt="background" src="{{ asset('img/home/home5.jpg') }}"/>
            </div>
            <div class="container pos-vertical-center">
                <div class="row">
                    <div class="col-md-8">
                        <img alt="Image" class="unmarg--bottom" src="{{ asset('img/home/logo-heading.png') }}"/>
                        <h3 style="font-weight: 600;">
                            Rent anything, right from your neighbourhood
                        </h3>
                        <a class="btn btn--primary type--uppercase btn--lg" href="{{ route('rent_categories') }}">
                                <span class="btn__text">
                                    Rent
                                </span>
                        </a>
                        <a class="btn btn--primary type--uppercase btn--lg" href="{{ route('lend_categories') }}">
                                <span class="btn__text">
                                    Lend
                                </span>
                        </a>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <div class="text-center">
                <a id="scroll_to_content" href="#content">
                    <i class="stack-interface stack-down-open-big"></i>
                </a>
            </div>
            <!--end of container-->
        </section>
        <a id="content"></a>
        <section class="text-center" id="about">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <h2>What we do?</h2>
                        <p class="lead">
                            It’s pretty simple! We connect the lenders with the renters to encourage the business of
                            renting products and commodities in the same neighbourhood.
                        </p>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        {{--<section class="text-center bg--secondary">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-lg-6">
                        <h2>The process is simple.</h2>
                        <div class="video-cover border--round">
                            <div class="background-image-holder">
                                <img alt="image" src="{{ asset('img/blog-3.jpg') }}" />
                            </div>
                            <div class="video-play-icon"></div>
                            <iframe data-src="https://www.youtube.com/embed/6p45ooZOOPo?autoplay=1" allowfullscreen="allowfullscreen"></iframe>
                        </div>
                        <!--end video cover-->
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>--}}
        @guest
            <section class="bg--primary unpad cta cta-2" id="video">
                <a href="{{ route('register') }}">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2>Become a RentingHood neighbour</h2>
                            </div>
                        </div>
                        <!--end of row-->
                    </div>
                    <!--end of container-->
                </a>
            </section>
        @endguest
    </div>
    <script>
        $(document).ready(function () {

            @auth
            function getMessageCount() {

                $.ajax({

                    url: '{{ route('get_message_count') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {

                        if (parseInt(response.requests_count) + parseInt(response.replies_count) != 0) {

                            $('#messages_modal_template').find('.modal-trigger').trigger('click');

                        }
                    }
                });
            }

            getMessageCount();
            @endauth

            $('#scroll_to_content').click(function (e) {

                e.preventDefault();
                scrollToItem($($(this).attr('href')));

            });

            function scrollToItem(item) {

                $('html, body').animate({
                    scrollTop: item.offset().top
                }, 1000);

            }

        });

    </script>
@endsection