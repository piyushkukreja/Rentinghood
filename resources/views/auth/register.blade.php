@extends('layouts.app')
@extends('layouts.navbar')
@section('content')
    <div class="main-container">
        <section class="imageblock switchable feature-large height-100 bg--light">
            <div class="imageblock__content col-lg-6 col-md-4 pos-right">
                <div class="background-image-holder">
                    <img alt="image" src="{{ asset('img/inner-7.jpg') }}" />
                </div>
            </div>
            <div class="container pos-vertical-center">
                <div class="row">
                    <div class="col-lg-5 col-md-7">
                        <h2>Become a Rentinghood neighbour</h2>

                        <form method="POST" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            <div class="row">

                                <div class="col-md-6">
                                    <input id="first_name" type="text"  name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required autofocus>

                                    @if ($errors->has('first_name'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <input id="last_name" type="text" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}" required>

                                    @if ($errors->has('last_name'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <input id="email" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <input id="pin_code" type="text" placeholder="Pin-code" name="pin_code" value="{{ old('pin_code') }}">
                                </div>

                                <div class="col-md-12">
                                    <input id="full_address" type="text" placeholder="Full Address" name="pin_code" value="{{ old('full_address') }}" disabled>
                                </div>

                                <div class="col-md-12">
                                    <input id="contact" placeholder="Contact" type="number" name="contact" value="{{ old('contact') }}" required>

                                    @if ($errors->has('contact'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('contact') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <input id="password" placeholder="Password" type="password"  name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <input id="password-confirm" placeholder="Confirm Password" type="password"  name="password_confirmation" required>
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


{{--jquey--}}
<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

{{----}}

<script>

    $(document).ready(function () {

        $('#pin_code').on('change', function () {
            var full_address = '';
            var address = $(this).val();
            var i, j;
            if(parseInt(address.length) === 6) {
                $.ajax({

                    url: 'https://maps.googleapis.com/maps/api/geocode/json?address=' + address + '&key=AIzaSyDtfAuKKrycjdbscKGGfbCg0R5udw3N73g',
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {

                        if (response.status == 'OK') {

                            for (i = 0; i < response.results[0].address_components.length; i++) {
                                for (j = 0; j < response.results[0].address_components[i].types.length; j++) {
                                    if (response.results[0].address_components[i].types[j] == 'locality') {
                                        full_address += response.results[0].address_components[i].long_name + ', ';
                                        break;
                                    }
                                    else if (response.results[0].address_components[i].types[j] == 'administrative_area_level_2') {
                                        full_address += response.results[0].address_components[i].long_name + ', ';
                                        break;
                                    }
                                    else if (response.results[0].address_components[i].types[j] == 'administrative_area_level_1') {
                                        full_address += response.results[0].address_components[i].long_name + '';
                                        break;
                                    }
                                }
                            }
                            $('#full_address').val(full_address);

                        }

                    }
                });
            }
        })

    })
</script>

@endsection
