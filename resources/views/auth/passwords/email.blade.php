@extends('layouts.app')
@extends('layouts.navbar')
@section('content')
    <div class="main-container" style="min-height: 100vh;">
        <section class="text-center bg--light" style="padding-top: 10em;">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-lg-5">
                        <h2>Recover your account</h2>
                        <p class="lead">
                            Enter email address to send a recovery email.
                        </p>
                        <form method="POST" action="{{ route('password.email') }}">
                            {{ csrf_field() }}
                            <input id="email" name="email" type="email" placeholder="Email Address" value="{{ old('email') }}" required>
                            @if ($errors->has('email'))
                                <span class="color--error">
                                        <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                            <button class="btn btn--primary type--uppercase" type="submit">Recover Account</button>
                        </form>
                        <span class="type--fine-print block">Dont have an account yet?
                                <a href="{{ route('register') }}">Create account</a>
                        </span>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
    </div>
@endsection
