@extends('layouts.app')
@extends('layouts.navbar')
@section('content')
    <style>
        .main-container {
            min-height: 100vh;
        }
    </style>
    <div class="main-container bg--secondary">
        <section class="text-center bg--secondary" style="padding-top: 10em;">
            <div class="container">
                <div class="row" id="send_otp_div">
                    <div class="col-md-7 col-lg-5 boxed boxed--border border--round box-shadow">
                        <h2>Verify Your Contact</h2>
                        <p class="lead">
                            We will send a OTP to your mobile ******{{ substr(Auth::user()->contact, 6, 4) }}
                        </p>
                        <form id="send_otp_form">
                            {{ csrf_field() }}
                            <button id="send_otp" class="btn btn--primary type--uppercase" style="margin-top: 0;" type="submit">Send</button>
                        </form>
                    </div>
                </div>
                <div class="row" id="otp_div" style="display: none;">
                    <div class="col-md-7 col-lg-5 boxed boxed--border border--round box-shadow">
                        <h2>Verify Your Contact</h2>
                        <p class="lead">
                            Enter OTP sent to ******{{ substr(Auth::user()->contact, 6, 4) }}
                        </p>
                        <form id="otp_form">
                            {{ csrf_field() }}
                            <input id="otp" placeholder="OTP" type="number" class="form-control" name="otp" required autofocus>
                            <button id="verify_otp" class="btn btn--primary type--uppercase" type="submit">Verify</button>
                        </form>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
    </div>

<script type="text/javascript">

    //js for opt
    $(document).ready(function () {

        $('#send_otp').on('click', function (e) {

            var send_button = $('#send_otp');
            send_button.addClass('disabled').html('Sending...');
            e.preventDefault();
            e.stopPropagation();
            $.ajax({
                    type: 'GET',
                    url: '{{ route('send_OTP') }}',
                    success: function (data) {

                        send_button.removeClass('disabled').html('Send');
                        $('#send_otp_div').slideUp(function () {

                            $('#otp_div').slideDown();

                        });

                    }
                });
        });

        $('#otp_form').on('submit', function (e) {

            e.preventDefault();
            e.stopPropagation();
            var verify_button = $('#verify_otp');
            $('#otp_message').remove();
            verify_button.html('Verifying...').addClass('disabled');
            $.ajax(
                {
                    type: 'POST',
                    url: '{{ route('verify_OTP') }}',
                    data: $('form').serialize(),
                    dataType: "json",
                    success: function(data){
                        if(data.status == 'failed') {
                            $('#otp').after('<span id="otp_message" class="color--dark"><strong>Invalid OTP</strong></span>');
                            verify_button.html('Verify');
                            verify_button.removeClass('disabled');
                        }
                        else {
                            window.location.replace('{{ route('account') }}');
                        }
                    }
                }
            );
        });

    })
</script>
    
@endsection
