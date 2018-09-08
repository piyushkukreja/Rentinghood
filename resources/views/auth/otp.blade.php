@extends('layouts.public')
@extends('layouts.public_parts.navbar_blue')
@section('head')
    @parent
    <style type="text/css">
        .main-container {
            min-height: 100vh;
        }
    </style>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">

        $(document).ready(function () {

            var sendOtpForm = $('#send_otp_form');
            var otpForm = $('#otp_form');
            var sendButton = $('#send_otp');
            var verifyButton = $('#verify_otp');

            //JS for OTP request
            sendButton.on('click', function (event) {
                event.preventDefault();
                sendButton.addClass('disabled').html('Sending...');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('send_OTP') }}',
                    data: sendOtpForm.serialize(),
                    success: function (data) {
                        console.log(data);
                        sendButton.removeClass('disabled').html('Send');
                        $('#send_otp_div').slideUp(function () {
                            $('#otp_div').slideDown();
                        });
                    }
                });
            });

            //JS for sending entered OTP
            otpForm.on('submit', function (event) {
                event.preventDefault();
                $('#otp_message').remove();
                verifyButton.html('Verifying...').addClass('disabled');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('verify_OTP') }}',
                    data: otpForm.serialize(),
                    dataType: 'JSON',
                    success: function(data){
                        if(data.status === 'failed') {
                            $('#otp').after('<span id="otp_message" class="color--dark"><strong>Invalid OTP</strong></span>');
                            verifyButton.html('Verify');
                            verifyButton.removeClass('disabled');
                        }
                        else
                            window.location.replace('{{ route('account') }}');
                    }
                });
            });
        });
    </script>
@endsection
@section('content')
    <div class="main-container bg--secondary">
        <section class="text-center bg--secondary" style="padding-top: 10em;">
            <div class="container">
                <div class="row" id="send_otp_div">
                    <div class="col-md-7 col-lg-5 boxed boxed--border border--round box-shadow">
                        <h2>Verify Your Contact</h2>
                        @if(Auth::user()->hasContact())
                            <p class="lead">
                                We will send a OTP to your mobile ******{{ substr(Auth::user()->contact, 6, 4) }}
                            </p>
                            <form id="send_otp_form">
                                {{ csrf_field() }}
                                <button id="send_otp" class="btn btn--primary type--uppercase" style="margin-top: 0;" type="submit">Send</button>
                            </form>
                        @else
                            <p class="lead">
                                Enter your mobile number. We will send a OTP to this number.
                            </p>
                            <form id="send_otp_form">
                                {{ csrf_field() }}
                                <input id="contact" name="contact" placeholder="Contact" type="number" class="form-control" required autofocus>
                                <button id="send_otp" class="btn btn--primary type--uppercase" type="submit">Send</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="row" id="otp_div" style="display: none;">
                    <div class="col-md-7 col-lg-5 boxed boxed--border border--round box-shadow">
                        <h2>Verify Your Contact</h2>
                        @if(Auth::user()->hasContact())
                            <p class="lead">
                                Enter OTP sent to ******{{ substr(Auth::user()->contact, 6, 4) }}
                            </p>
                        @else
                            <p class="lead">
                                Enter OTP sent to the entered mobile number
                            </p>
                        @endif
                        <form id="otp_form">
                            {{ csrf_field() }}
                            <input id="otp" placeholder="OTP" type="number" class="form-control" name="otp" required>
                            <button id="verify_otp" class="btn btn--primary type--uppercase" type="submit">Verify</button>
                        </form>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
    </div>
@endsection
