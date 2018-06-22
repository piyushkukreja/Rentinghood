@extends('layouts.public')
@extends('layouts.public_parts.navbar_blue')
@section('content')
    <style>
        @media (max-width: 767px) {
            .map_canvas {
                min-height: 50vh;
            }
            .main-container > section h2 {
                margin-top: 1em;
            }
        }
        .main-container > section {
            font-size: 1.05em;
        }
        .main-container > section h2 {
            color: #fff;
        }
    </style>
    <div class="main-container">
        <section class="feature-large height-100 imagebg" data-overlay="5">
            <div class="background-image-holder">
                <img alt="background" src="{{ asset('img/register-bg.jpg') }}">
            </div>
            <div class="container pos-vertical-center">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="map_canvas h-100"></div>
                    </div>
                    <div class="col-md-6 col-12">
                        <h2 style="font-weight: 500;">Become a RentingHood neighbour</h2>

                        <form method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <div class="row">

                                <div class="col-md-6">
                                    <input id="first_name" type="text" name="first_name" placeholder="First Name"
                                           value="{{ old('first_name') }}" required autofocus>

                                    @if ($errors->has('first_name'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" placeholder="Last Name" name="last_name"
                                           value="{{ old('last_name') }}" required>

                                    @if ($errors->has('last_name'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <input id="email" type="email" placeholder="Email" name="email"
                                           value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <input id="address" type="text" placeholder="Address" name="address"
                                           value="{{ old('address') }}" required>
                                </div>

                                <div id="latlng" class="hidden">
                                    <input id="lat" type="hidden" name="lat" value="">
                                    <input id="lng" type="hidden" name="lng" value="">
                                </div>

                                <div class="col-md-12">
                                    <input id="contact" placeholder="Contact" type="number" name="contact"
                                           value="{{ old('contact') }}" required>

                                    @if ($errors->has('contact'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('contact') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <input id="password" placeholder="Password" type="password" name="password"
                                           required>

                                    @if ($errors->has('password'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <input id="password-confirm" placeholder="Confirm Password" type="password"
                                           name="password_confirmation" required>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn btn--primary type--uppercase">
                                        Register
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
    </div>

    <script>

        $(document).ready(function () {

            var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(19.296441, 72.9864994),
                new google.maps.LatLng(18.8465126, 72.9042434)
            );
            $("#address").geocomplete({
                location: 'Mumbai',
                map: ".map_canvas",
                details: "#latlng",
                bounds: defaultBounds
            }).bind("geocode:result", function (event, result) {
                console.log(result);
            });

        })
    </script>

@endsection
