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

                                <div class="col-md-6">
                                    <div class="input-select">
                                        <select id="city_id" name="city_id" required>
                                            <option value="" disabled selected>City</option>
                                            @foreach( $cities as $city )
                                                <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? "selected" : "" }}>{{ ucwords($city->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('city_id'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('city_id') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <div class="input-select">
                                        <select id="pin_code_id"  name="pin_code_id" required>
                                        </select>
                                    </div>
                                    @if ($errors->has('pin_code_id'))
                                        <span class="color--error">
                                        <strong>{{ $errors->first('pin_code_id') }}</strong>
                                    </span>
                                    @endif
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

        $('#pin_code_id').append('<option value="" disabled selected>Pin Code</option>');

        function changePinCodes(val = 0) {

            var pin_code_selector = $('#pin_code_id');
            pin_code_selector.empty();
            var city_id = $('#city_id').val();
            var csrf_token = '{{ csrf_token() }}';
            $.ajax(
                {
                    type: 'POST',
                    url: '{{ route('get_pin_codes') }}',
                    data: { _token: csrf_token, city_id: city_id },
                    dataType: 'JSON',
                    success: function(returned_data){

                        pin_code_selector.append('<option value="" disabled selected>Pin Code</option>');

                        $.each(returned_data, function(i, d) {

                            if (val == d.id) {
                                pin_code_selector.append('<option value="' + d.id + '" selected>' + d.pin_code + '</option>');
                            }
                            else {
                                pin_code_selector.append('<option value="' + d.id + '">' + d.pin_code + '</option>');
                            }

                        });

                    }
                }
            )
        }

        changePinCodes({{ old('pin_code_id') }});

        $('#city_id').on('change', function () {
            changePinCodes(0);
        });

    })
</script>

@endsection
