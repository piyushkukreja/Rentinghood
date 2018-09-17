@extends('layouts.public')
@extends('layouts.public_parts.navbar_transparent')
@section('content')
    <div class="main-container">
        <section class="height-100 imagebg text-center" data-overlay="6">
            <div class="background-image-holder">
                <img alt="background" src="{{ asset('img/login-bg.jpg') }}"/>
            </div>
            <div class="container pos-vertical-center">
                <div class="row">
                    <div class="col-md-7 col-lg-5">
                        <h2>Login to continue</h2>
                        {{--<a class="btn block btn--icon bg--facebook type--uppercase" href="{{ route('social-login', ['facebook']) }}">
                                <span class="btn__text">
                                    <i class="socicon-facebook"></i>
                                    Login with Facebook
                                </span>
                        </a>--}}
                        <a class="btn block btn--icon bg--googleplus type--uppercase" href="{{ route('social-login', ['google']) }}">
                                <span class="btn__text">
                                    <i class="socicon-google"></i>
                                    Login with Google +
                                </span>
                        </a>
                        <hr>
                        <p class="lead">
                            Welcome back, sign in with your existing RentingHood account credentials
                        </p>
                        <form method="POST" action="{{ route('login') }}">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <input id="email" type="email" name="email"
                                           value="{{ old('email') }}" placeholder="Email" required autofocus>

                                    @if ($errors->has('email'))
                                        <span>
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <input id="password" type="password" name="password" placeholder="Password" required>

                                    @if ($errors->has('password'))
                                        <span>
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn--primary type--uppercase" type="submit">Login</button>
                                </div>
                            </div>
                            <!--end of row-->
                        </form>
                        <span class="type--fine-print block">Dont have an account yet?
                                <a href="{{ route('register') }}">Create account</a>
                            </span>
                        <span class="type--fine-print block">Forgot your password?
                                <a href="{{ route('recover_form') }}">Recover account</a>
                            </span>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
    </div>
    <!--<div class="loader"></div>-->
    <a class="back-to-top inner-link" href="#start" data-scroll-class="100vh:active">
        <i class="stack-interface stack-up-open-big"></i>
    </a>

@endsection