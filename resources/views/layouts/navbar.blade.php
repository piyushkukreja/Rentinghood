@section('navbar')
    <style>
        .menu-horizontal > li:not(:hover) > a {
            opacity: 1;
            color: white;
        }
        .menu-horizontal > li:hover > a {
            opacity: 1;
            color: white;
        }
        .menu-horizontal li:hover {
            transform: translate(0, -5px);
        }
        .menu-horizontal li {
            transition: transform .3s ease-out;
        }
        #menu1, .nav-container {
            background: #03A9F4;
        }
        #menu1 .btn--primary {
            background: #303F9F;
            border-color: #303F9F;
        }
        #register_nav_button {
            border-color: white;
        }
        #register_nav_button .btn__text {
            color: white;
        }
        .bar-1 .menu-horizontal > li > .dropdown__trigger, .bar-1 .menu-horizontal > li > a {
            font-size: 1em;
            line-height: 2.166666666666667em;
            text-transform: none;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        #location_field, #swal_location_field {
            font-family: FontAwesome, "Open Sans", Verdana, sans-serif;
            font-style: normal;
            font-weight: normal;
            text-decoration: inherit;
        }
    </style>
    <div class="nav-container">
        <div class="bar bar--sm visible-xs bar--mobile-sticky box-shadow" data-scroll-class="59px:pos-fixed" style="background: #03A9F4;">
            <div class="container">
                <div class="row">
                    <div class="col-8 col-md-2">
                        <a href="{{ route('home') }}">
                            <h2 style="font-weight: 900; margin-bottom: 0; color: white; display: inline-block;">
                                rentinghood
                            </h2>
                        </a>
                    </div>
                    <div class="col-4 col-md-10 text-right">
                        <a href="#" class="hamburger-toggle" data-toggle-class="#menu1;hidden-xs">
                            <i style="color: #ffffff;" class="icon icon--sm stack-interface stack-menu"></i>
                        </a>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </div>
        <!--end bar-->
        <nav id="menu1" class="bar bar--sm bar-1 hidden-xs box-shadow" data-scroll-class='64px:pos-fixed'>
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-4 hidden-xs">
                        <div class="bar__module" style="margin-bottom: 0;">
                            <a href="{{ route('home') }}" >
                                <h3 style="font-weight: 900; margin-bottom: 0; color: white; display: inline;">
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
                                <li><a href="{{ route('contact') }}">Contact Us</a></li>
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
                                <a id="register_nav_button" class="btn btn--sm type--uppercase" href="{{ route('register') }}">
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
        @yield('location_field')
    </div>
@endsection