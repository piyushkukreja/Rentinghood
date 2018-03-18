@extends('layouts.app')
@extends('layouts.navbar')
@section('content')
    <div class="main-container">
        <section class="feature-large height-100 image--light imagebg">
            <div class="background-image-holder">
                <img alt="background" src="{{ asset('img/agency-1.jpg') }}">
            </div>
            <div class="container pos-vertical-center">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <div class="map_canvas h-100"></div>
                    </div>
                    <div class="col-md-6 col-12">
                        <h2 style="font-weight: 500;">Become a Rentinghood neighbour</h2>

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
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDtfAuKKrycjdbscKGGfbCg0R5udw3N73g&amp;libraries=places"></script>
    <script src="{{ asset('js/jquery.geocomplete.min.js') }}"></script>

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
