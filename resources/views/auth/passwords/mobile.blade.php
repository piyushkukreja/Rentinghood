@extends('layouts.public')
@extends('layouts.public_parts.navbar_blue')
@section('content')
    <style>
        #otp_form button, #recover_form button{
            margin-top: 1.5em;
        }

    </style>
    <div class="main-container bg--secondary" style="min-height: 100vh;">
        <section class="text-center bg--secondary" style="padding-top: 10em;">
            <div class="container">
                <div id="recover_div" class="row">
                    <div class="col-md-7 col-lg-5 boxed boxed--border border--round box-shadow">
                        <h2>Recover your account</h2>
                        <p class="lead">
                            Enter contact to send a recovery OTP.
                        </p>
                        <form id="recover_form" method="POST" action="{{ route('recover') }}">
                            {{ csrf_field() }}
                            <input id="contact" name="contact" type="number" placeholder="Contact" value="{{ old('contact') }}" required>
                            <button id="send_otp" class="btn btn--primary type--uppercase" type="submit">Recover Account</button>
                        </form>
                    </div>
                </div>
                <!--end of row-->

                <div id="otp_div" class="row" style="display: none;">
                    <div class="col-md-7 col-lg-5 boxed boxed--border border--round box-shadow">
                        <h2>Verify yourself</h2>
                        <p class="lead">
                            Enter OTP that has been sent to the entered contact.
                        </p>
                        <form id="otp_form" method="POST" action="{{ route('recover_verify_OTP') }}">
                            {{ csrf_field() }}
                            <input id="otp" placeholder="OTP" type="number" class="form-control" name="otp" required autofocus>
                            <button id="verify_otp" class="btn btn--primary type--uppercase" type="submit">Verify</button>
                        </form>
                    </div>
                </div>
                <!--end of row-->

                <div id="password_div" class="row" style="display: none;">
                    <div id="password_div" class="col-md-7 col-lg-5 boxed boxed--border border--round box-shadow">
                        <h2>Reset your password</h2>
                        <p class="lead">
                            Enter new password and confirm to recover your account.
                        </p>
                        <form id="password_form" method="POST" action="{{ route('reset_password') }}">
                            {{ csrf_field() }}
                            <input id="reset_code" name="reset_code" type="hidden" value="0">
                            <input id="password" placeholder="Password" type="password" class="form-control" name="password" required>
                            <input id="password-confirm" placeholder="Confirm Passoword" type="password" class="form-control" name="password_confirmation" required>
                            <button id="reset_password" class="btn btn--primary type--uppercase" type="submit">Reset Password</button>
                        </form>
                    </div>
                </div>
                <!--end of row-->

                    {{--<span class="type--fine-print block">Dont have an account yet?
                                <a href="{{ route('register') }}">Create account</a>
                        </span>--}}

            </div>
            <!--end of container-->
        </section>
    </div>

    <script type="text/javascript">

        $(document).ready(function () {

            //js for sending otp
            $('#recover_form').on('submit', function (e) {

                e.preventDefault();
                e.stopPropagation();
                var send_otp_button = $('#send_otp');
                $('#contact_message').remove();
                $('#contact_error').remove();
                send_otp_button.html('Verifying...').addClass('disabled');
                $.ajax(
                    {
                        type: 'POST',
                        url: '{{ route('recover') }}',
                        data: $('#recover_form').serialize(),
                        dataType: "json",
                        success: function (data) {
                            if (!data.exists) {
                                $('#contact').after('<span id="contact_message" class="color--dark"><strong>These credentials do not match our records.</strong></span>');
                                send_otp_button.html('Recover Account');
                                send_otp_button.removeClass('disabled');
                            }
                            else {
                                $('#recover_div').slideUp(function () {
                                    $('#otp_div').slideDown();
                                });
                            }
                        },
                        error: function (xhr, status, error) {

                            send_otp_button.html('Recover Account');
                            send_otp_button.removeClass('disabled');
                            var errors = JSON.parse(xhr.responseText);
                            var contact_error = errors["errors"]["contact"][0];
                            $('#recover_form').find('#contact').after('<span id="contact_error" class="color--dark"><strong>' + contact_error   + '</strong></span>');

                        }
                    }
                );
            });

            //js for verifying otp
            $('#otp_form').on('submit', function (e) {
                var verify_button = $('#verify_otp');
                e.preventDefault();
                e.stopPropagation();
                $('#otp_message').remove();
                verify_button.html('Verifying...').addClass('disabled');
                $.ajax(
                    {
                        type: 'POST',
                        url: '{{ route('recover_verify_OTP') }}',
                        data: $('form').serialize(),
                        dataType: 'json',
                        success: function(data){
                            if(data.status === 'failed') {
                                $('#otp').after('<span id="otp_message" class="color--dark"><strong>Invalid OTP</strong></span>');
                                verify_button.html('Verify');
                                verify_button.removeClass('disabled');
                            }
                            else {
                                $('#reset_code').val(data.reset_code);
                                $('#otp_div').slideUp(function () {
                                    $('#password_div').slideDown();
                                });
                            }
                        }
                    }
                );
            });

            //js for password reset
            $('#password_form').on('submit', function (e) {
                e.preventDefault();
                e.stopPropagation();
                var password_button = $('#reset_password');
                $('#password_message').remove();
                $('#password_error').remove();
                password_button.html('Resetting...').addClass('disabled');
                $.ajax(
                    {
                        type: 'POST',
                        url: '{{ route('reset_password') }}',
                        data: $('form').serialize(),
                        dataType: 'json',
                        success: function(data){
                            if(data.status == 'failed') {
                                $('#password-confirm').after('<span id="password_message" class="color--dark"><strong>Invalid reset code.</strong></span>');
                                password_button.html('Reset Password').removeClass('disabled');
                            }
                            else {
                                window.location.replace('{{ route('login') }}');
                            }
                        },
                        error: function (xhr, status, error) {

                            password_button.html('Reset Password');
                            password_button.removeClass('disabled');
                            var errors = JSON.parse(xhr.responseText);
                            var password_error = errors["errors"]["password"][0];
                            $('#password_form').find('#password').after('<span id="password_error" class="color--dark"><strong>' + password_error   + '</strong></span>');

                        }
                    }
                );
            });

        })
    </script>

@endsection
