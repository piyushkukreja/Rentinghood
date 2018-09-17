@extends('layouts.public')
@extends('layouts.public_parts.navbar_blue')
@section('head')
    @parent
    <style type="text/css">
        .main-container {
            min-height: 100vh;
        }
        button.custom-button {
            font-size: 1.1em;
        }
    </style>
@endsection
@if(!Auth::user()->hasContactVerified())
    @section('scripts')
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            var sendOtpForm = $('#send-otp-form');
            var otpForm = $('#otp-form');
            var sendButton = $('#send-otp');
            var verifyButton = $('#verify-otp');

            //JS for OTP request
            sendButton.on('click', function (event) {
                event.preventDefault();
                sendButton.addClass('disabled').html('Sending...');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('otp.send') }}',
                    data: sendOtpForm.serialize(),
                    success: function (data) {
                        console.log(data);
                        sendButton.removeClass('disabled').html('Send');
                        $('#send-otp-div').slideUp(function () {
                            $('#otp-div').slideDown();
                        });
                    }
                });
            });

            //JS for sending entered OTP
            otpForm.on('submit', function (event) {
                event.preventDefault();
                $('#otp-message').remove();
                verifyButton.html('Verifying...').addClass('disabled');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('otp.verify') }}',
                    data: otpForm.serialize(),
                    dataType: 'JSON',
                    success: function(data){
                        if(data.status === 'failed') {
                            $('#otp').after('<span id="otp-message" class="color--dark"><strong>Invalid OTP</strong></span>');
                            verifyButton.html('Verify');
                            verifyButton.removeClass('disabled');
                        }
                        else {
                            @if(isset($_GET['redirect_to']))
                                $('#otp-div').slideUp();
                            @else
                                window.location.href = '{{ route('account') }}';
                            @endif
                        }
                    }
                });
            });
        });
    </script>
@endsection
@endif
@section('content')
    <div class="main-container bg--secondary">
        <section class="text-center bg--secondary" style="padding-top: 5em;">
            <div class="container">
                @if(!Auth::user()->hasVerifiedEmail())
                <div class="row" id="send-email-div" style="margin-bottom: 2em;">
                    <div class="col-md-7 col-lg-5 boxed boxed--border border--round box-shadow">
                        <h2>Verify Your Email Address</h2>
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                A fresh verification link has been sent to your email address.
                            </div>
                        @endif
                        <p class="lead">Before proceeding, please check your email for a verification link.</p>
                        If you did not receive the email, <a href="{{ route('verification.resend') }}">click here to request another</a>.
                    </div>
                </div>
                <!--end of row-->
                @endif
                @if(!Auth::user()->hasContactVerified())
                <div class="row" id="send-otp-div">
                    <div class="col-md-7 col-lg-5 boxed boxed--border border--round box-shadow">
                        <h2>Verify Your Contact</h2>
                        @if(Auth::user()->hasContact())
                            <p class="lead">
                                We will send a OTP to your mobile ******{{ substr(Auth::user()->contact, 6, 4) }}
                            </p>
                            <form id="send-otp-form">
                                {{ csrf_field() }}
                                <button id="send-otp" class="btn btn--primary custom-button" style="margin-top: 0;" type="submit">Send</button>
                            </form>
                        @else
                            <p class="lead">
                                Enter your mobile number. We will send a OTP to this number.
                            </p>
                            <form id="send-otp-form">
                                {{ csrf_field() }}
                                <input id="contact" name="contact" placeholder="Contact" type="number" class="form-control" required autofocus>
                                <button id="send-otp" class="btn btn--primary custom-button" type="submit">Send</button>
                            </form>
                        @endif
                    </div>
                </div>
                <!--end of row-->
                <div class="row" id="otp-div" style="display: none;">
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
                        <form id="otp-form">
                            {{ csrf_field() }}
                            <input id="otp" placeholder="OTP" type="number" class="form-control" name="otp" required>
                            <button id="verify-otp" class="btn btn--primary custom-button" type="submit">Verify</button>
                        </form>
                    </div>
                </div>
                <!--end of row-->
                @endif
                @if(isset($_GET['redirect_to']))
                <div class="row" style="margin-top: 1em;">
                    <div class="col-md-6 offset-md-3 text-center">
                        <p class="lead">Done with verification? <a href="{{ $_GET['redirect_to'] }}">Lets get back!</a>.
                    </div>
                </div>
                @endif
            </div>
        </section>
    </div>
@endsection