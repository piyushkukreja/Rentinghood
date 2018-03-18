@extends('layouts.app')
@extends('layouts.navbar')
@section('content')

    <style>
        ol.breadcrumbs, ol.breadcrumbs > li {
            margin-bottom: 0;
        }

        label {
            font-size: 1em;
        }

        #from, #to {
            cursor: pointer;
        }

        .pricing__head.boxed {
            padding: 1em;
        }

        #verify_otp {
            margin-top: 1em;
        }

        .modal-container .modal-content .container {
            max-width: 100%;
        }

        .modal-container .modal-content {
            width: auto; !important;
            padding: 0;
            max-width: 90vw;
        }

        #otp_modal .boxed {
            margin-bottom: 0;
        }

        @media (min-width: 576px)  {
            .modal-container .modal-content {
                width: auto; !important;
                max-width: 90vw;
            }

            #login_modal .modal-content, #otp_modal .modal-content { max-width: 500px; }


        }

        @media (min-width: 768px) {

            .modal-container .modal-content { max-width: 70vw; }

            .picker__weekday { padding: 0.6em; }

            #login_modal .modal-content, #otp_modal .modal-content { max-width: 500px; }

        }

        @media (min-width: 992px) {  .picker__weekday { padding: 0.9em; } }

        @media (max-width: 990px) {
            #product_hr {
                margin-top: 0;
                margin-bottom: 0.5em;
            }
        }

        #contact_owner {

            font-size: 1em;
            margin-top: 0.5em;
            margin-bottom: 5em;

        }
    </style>
    <div class="main-container">
        <section class="bg--secondary" style="padding-bottom: 3em; padding-top: 3em;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>{{ ucwords($product->name) }}</h1>
                        <ol class="breadcrumbs">
                            <li>
                                <a href="{{ route('home') }}">Home</a>
                            </li>
                            <li>
                                <a href="{{ route('rent_categories') }}">Rent</a>
                            </li>
                            <li>
                                <a href="{{ route('rent_subcategories', array('category_name' => $product->category->name)) }}">{{ ucwords($product->category->name) }}</a>
                            </li>
                            <li>{{ ucwords($product->name) }}</li>
                        </ol>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section style="padding-top: 2em;">
            <div class="container">
                <div class="row justify-content-around">
                    <div class="col-md-6 col-lg-6">
                        <div class="slider border--round boxed--border" data-paging="true" data-arrows="true"
                             data-autoplay="false">
                            <ul class="slides">
                                @foreach($product_pictures as $picture)
                                    <li>
                                        <img alt="Image"
                                             src="{{ asset('img/uploads/products/large/' . $picture->file_name) }}"/>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!--end slider-->
                    </div>
                    <div class="col-md-6 col-lg-6">
                        <h2>{{ ucwords($product->name) }}</h2>
                        <p>
                            {{ $product->description }}
                        </p>
                        <div class="row">
                            <div class="col-md-6 col-lg-4">
                                <div class="pricing pricing-3">
                                    <div class="pricing__head bg--secondary boxed">
                                            <span class="h3">
                                                        &#8377;{{ $product->rate_1 }}</span>
                                        <p class="type--fine-print">Per Day.</p>
                                    </div>
                                </div>
                                <!--end pricing-->
                            </div>
                            @if($product->duration > 0)
                                <div class="col-md-6 col-lg-4">
                                    <div class="pricing pricing-3">
                                        <div class="pricing__head bg--secondary boxed">
                                                <span class="h3">
                                                            &#8377;{{ $product->rate_2 }}</span>
                                            <p class="type--fine-print">Per Week.</p>
                                        </div>
                                    </div>
                                    <!--end pricing-->
                                </div>
                            @endif
                            @if($product->duration > 1)
                                <div class="col-md-6 col-lg-4">
                                    <div class="pricing pricing-3">
                                        <div class="pricing__head bg--secondary boxed">
                                                <span class="h3">
                                                            &#8377;{{ $product->rate_3 }}</span>
                                            <p class="type--fine-print">Per Month.</p>
                                        </div>
                                    </div>
                                    <!--end pricing-->
                                </div>
                            @endif
                        </div>
                        <!--end of row-->
                        <hr id="product_hr">
                        <form id="contact_owner_form" method="post" action="{{ route('contact_owner') }}">
                            {{ csrf_field() }}
                            <div id="availability_message" class="col-12 h5 color--error boxed boxed--border"
                                 style="padding: 1em; display: none;">
                                This product may not be available for the selected range.
                            </div>
                            <div class="col-md-12">
                                <label>Duration :</label>
                                <input type="text" id="from" name="from" placeholder="From" required>
                                <input type="text" id="to" name="to" placeholder="to" required>
                            </div>
                            <hr>
                            <div class="col-12 text-center">
                                <button id="contact_owner" type="submit" class="btn btn--primary">
                                    Place a request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @guest

        <!-- LOGIN MODAL   -->
        <div class="modal-instance">
            <a id="login_modal_trigger" href="#" class="modal-trigger hidden">Login</a>
            <div id="login_modal" class="modal-container">
                <div class="modal-content section-modal">
                    <section class="unpad ">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="boxed boxed--lg text-center feature">
                                        <div class="modal-close modal-close-cross"></div>
                                        <div class="text-block">
                                            <h3>Login to Your Account</h3>
                                            <p>
                                                Welcome back, sign in with your existing account credentials.
                                            </p>
                                        </div>
                                        <div class="feature__body">
                                            <form id="login_form" method="POST" action="{{ route('login') }}">
                                                {{ csrf_field() }}
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <input id="email" type="email" name="email" value="" placeholder="Email"
                                                               required autofocus>

                                                    </div>
                                                    <div class="col-md-12">
                                                        <input id="password" type="password" name="password" placeholder="Password"
                                                               required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <button class="btn btn--primary type--uppercase" type="submit">
                                                            Login
                                                        </button>
                                                    </div>
                                                </div>

                                            </form>
                                            <span class="type--fine-print block">
                                                Dont have an account yet? <a id="register_link" href="#">Create account</a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <!-- REGISTER MODAL   -->
        <div class="modal-instance">
            <a id="register_modal_trigger" class="btn modal-trigger hidden" href="#"></a>
            <div id="register_modal" class="modal-container">
                <div class="modal-content">
                    <section class="imageblock feature-large border--round ">
                        <div class="imageblock__content col-lg-5 col-md-3 pos-left">
                            <div class="background-image-holder">
                                <img alt="image" src="{{ asset('img/cowork-8.jpg') }}">
                            </div>
                        </div>
                        <div class="container">
                            <div class="row justify-content-end">
                                <div class="col-lg-6 col-md-7">
                                    <div class="row">
                                        <div class="col-md-11 col-lg-10">
                                            <h1>Become a RentingHood neighbour</h1>
                                            <hr class="short">
                                            <form id="register_form" method="POST" action="{{ route('register') }}">
                                                {{ csrf_field() }}
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <input id="first_name" type="text" name="first_name" placeholder="First Name" value="" required autofocus>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <input id="last_name" type="text" placeholder="Last Name" name="last_name" value="" required>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <input id="email2" type="email" placeholder="Email" name="email" value="" required>
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
                                                        <input id="contact" placeholder="Contact" type="number"
                                                               name="contact" value="">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <input id="password2" placeholder="Password" type="password"
                                                               name="password" required>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <input id="password-confirm" placeholder="Confirm Password"
                                                               type="password" name="password_confirmation" required>
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
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="modal-close modal-close-cross"></div>
                </div>
            </div>
        </div>
    @endguest

    <!-- OTP VERIFICATION MODAL -->
    <div class="modal-instance">
        <a id="otp_modal_trigger" class="btn modal-trigger hidden" href="#"></a>
        <div id="otp_modal" class="modal-container">
            <div class="modal-content">
                <section class="unpad ">
                    <div class="container">
                        <div class="row" id="send_otp_div">
                            <div class="col-12 boxed boxed--border border--round box-shadow">
                                <h2>Verify Your Contact</h2>
                                <p class="lead">
                                    We will send a OTP to your mobile ******<span class="otp_contact">@auth{{ substr(Auth::user()->contact, 6, 4) }}@endauth</span>
                                </p>
                                <form id="send_otp_form">
                                    {{ csrf_field() }}
                                    <button id="send_otp" class="btn btn--primary type--uppercase" style="margin-top: 0;" type="submit">Send</button>
                                </form>
                            </div>
                        </div>
                        <div class="row" id="otp_div" style="display: none;">
                            <div class="col-md-12 boxed boxed--border border--round box-shadow">
                                <h2>Verify Your Contact</h2>
                                <p class="lead">
                                    Enter OTP sent to ******<span class="otp_contact">@auth{{ substr(Auth::user()->contact, 6, 4) }}@endauth</span>
                                </p>
                                <form id="otp_form" class="text-center">
                                    {{ csrf_field() }}
                                    <input id="otp" placeholder="OTP" type="number" class="form-control" name="otp" required autofocus>
                                    <button id="verify_otp" class="btn btn--primary type--uppercase" type="submit">Verify</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>



    <!-- CONTACT OWNER MODAL -->
    <div class="modal-instance">
        <a id="contact_modal_trigger" class="btn modal-trigger hidden" href="#"></a>
        <div class="modal-container">
            <div class="modal-content">
                <section class="imageblock feature-large bg--white border--round ">
                    <div class="imageblock__content col-lg-5 col-md-3 pos-left">
                        <div class="background-image-holder">
                            <img alt="image" src="{{ asset('img/checkmark.gif') }}">
                        </div>
                    </div>
                    <div class="container">
                        <div class="row justify-content-end">
                            <div class="col-md-9 col-lg-7">
                                <div class="row justify-content-center">
                                    <div class="col-md-11 col-lg-9">
                                        <h2>Your request has been placed.</h2>
                                        <p class="lead"><span id="lender_name"></span>, has received your request. If he
                                            approves the request he will get back to you shortly.</p>
                                    </div>
                                    <!--end of col-->
                                </div>
                                <!--end of row-->
                            </div>
                        </div>
                        <!--end of row-->
                    </div>
                    <!--end of container-->
                </section>
                <div class="modal-close modal-close-cross"></div>
            </div>
        </div>
    </div>
    <a id="scroll_to_end" href="#end" class="hidden"></a>

    <script src="{{ asset('js/datepicker.js') }}"></script>
    <script type="text/javascript">

        $(document).ready(function () {

            //js for OTP Modal
            $('#send_otp').on('click', function (e) {

                var send_button = $('#send_otp');
                send_button.addClass('disabled').html('Sending...');
                e.preventDefault();
                e.stopPropagation();
                $.ajax({
                    type: 'GET',
                    url: '{{ route('send_OTP') }}',
                    success: function (data) {

                        send_button.removeClass('disabled').html('Sent');
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
                                $('#otp').after('<span id="otp_message" class="color--error"><strong>Invalid OTP</strong></span>');
                                verify_button.html('Verify');
                                verify_button.removeClass('disabled');
                            }
                            else {
                                verify_button.html('Verify');
                                verify_button.removeClass('disabled');
                                $('#otp_modal').find('.modal-close').trigger('click');
                                $('#contact_owner').trigger('click');
                            }
                        }
                    }
                );
            });

            //js for Login Modal
            @guest
            var logged_in = false;
            var login_form = $('#login_form');
            login_form.submit(function (e) {

                e.preventDefault();
                e.stopPropagation();
                $('#login_error').remove();
                var formData = login_form.serialize();

                $.ajax({
                    type: 'POST',
                    url: login_form.attr('action'),
                    data: formData,
                    success: function (returned_data) {

                        $('.modal-close-cross').trigger('click');
                        $('#menu1').find("a[href='{{ route('login') }}']").html('' +
                            '<span class="btn__text">Logout</span>' +
                            '</a><form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>');
                        $('#menu1').find("a[href='{{ route('login') }}']").attr('onclick', 'event.preventDefault();document.getElementById(\'logout-form\').submit();');
                        $('#menu1').find('ul.menu-horizontal').append('<li><a href="{{ route('account') }}">Account</a></li>');
                        $('#menu1').find('#register_nav_button').remove();
                        logged_in = true;
                        $('.otp_contact').html(returned_data.contact.substr(6));
                        $('#contact_owner').trigger('click');
                    },
                    error: function (xhr, status, error) {

                        var errors = JSON.parse(xhr.responseText);
                        var email_error = errors["errors"]["email"][0];
                        $('#login_form').find('#email').after('<span id="login_error" class="color--error">' + email_error + '</span>');

                    }
                });

            });


            //js for Register Modal
            var register_form = $('#register_form');
            register_form.submit(function (e) {

                e.preventDefault();
                e.stopPropagation();
                $('.register_error').slideUp(function () {

                    $('.register_error').remove();

                });
                var formData = register_form.serialize();

                $.ajax({
                    type: 'POST',
                    url: register_form.attr('action'),
                    data: formData,
                    success: function (returned_data) {

                        $('.modal-close-cross').trigger('click');
                        $('#menu1').find("a[href='{{ route('login') }}']").html('' +
                            '<span class="btn__text">Logout</span>' +
                            '</a><form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>');
                        $('#menu1').find("a[href='{{ route('login') }}']").attr('onclick', 'event.preventDefault();document.getElementById(\'logout-form\').submit();');
                        $('#menu1').find('ul.menu-horizontal').append('<li><a href="{{ route('account') }}">Account</a></li>');
                        logged_in = true;
                        $('#otp_modal_trigger').trigger('click');
                    },
                    error: function (xhr, status, error) {

                        var errors = JSON.parse(xhr.responseText);
                        errors = errors["errors"];
                        for (var key in errors) {

                            if(errors.hasOwnProperty(key))
                                $('#register_form').find('[name="' + key + '"]').after('<span class="register_error color--error">' + errors[key][0] + '</span>');

                        }

                    }
                });

            });

            //js for Pin Codes
            var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(19.296441, 72.9864994),
                new google.maps.LatLng(18.8465126, 72.9042434)
            );
            $("#address").geocomplete({
                location: 'Mumbai',
                details: "#latlng",
                bounds: defaultBounds
            }).bind("geocode:result", function (event, result) {
                console.log(result);
            });


            @else
            var logged_in = true;
            @endguest

            //js for Placing a request
            var placed = false;
            $('#contact_owner_form').submit(function (e) {

                e.preventDefault();
                e.stopPropagation();
                if (!placed) {
                    var contact_button = $('#contact_owner');
                    var from_input = $('#from');
                    var to_input = $('#to');
                    $('#from_error').remove();
                    $('#to_error').remove();
                    var errors = false;

                    if (from_input.val() == '') {
                        from_input.after('<span id="from_error" style="display: none;" class="color--error">Please select a from date</span>');
                        $('#from_error').fadeIn();
                        errors = true;

                    }
                    if (to_input.val() == '') {
                        to_input.after('<span id="to_error" style="display: none;" class="color--error">Please select a to date</span>');
                        $('#to_error').fadeIn();
                        errors = true;
                    }
                    if (!errors) {
                        if (!logged_in) {

                            $('#register_link').on('click', function (e) {

                                e.preventDefault();
                                $('#login_modal').find('.modal-close').trigger('click');
                                $('#register_modal_trigger').trigger('click');

                            });
                            $('#login_modal_trigger').trigger('click');
                            return;
                        }
                        contact_button.addClass('disabled');
                        contact_button.html('Placing your request');
                        var contact_owner_form = $('#contact_owner_form');
                        contact_owner_form.append('<input name="product_id" type="hidden" value="{{ $product->id }}">');
                        var formData = contact_owner_form.serialize();

                        $.ajax({
                            type: 'POST',
                            url: contact_owner_form.attr('action'),
                            data: formData,
                            success: function (returned_data) {

                                if(returned_data.verified) {

                                    contact_button.html('Request placed');
                                    $('#lender_name').html(returned_data.name);
                                    $('#contact_modal_trigger').trigger('click');
                                    placed = true;

                                } else {

                                    contact_button.html('Place a request');
                                    contact_button.removeClass('disabled');
                                    $('#otp_modal_trigger').trigger('click');

                                }

                            }
                        });
                    }
                }
            });


            //js for Datepicker
            $('#from').on('click', function () {
                scrollToItem($(this));
            });
            $('#to').on('click', function () {
                scrollToItem($(this));
            });

            function scrollToItem(item) {

                $('html, body').animate({
                    scrollTop: item.offset().top
                }, 1000);

            }

            var from_$input = $('#from').pickadate({

                    formatSubmit: 'dd-mm-yyyy',
                    hiddenSuffix: '_date'

                }),
                from_picker = from_$input.pickadate('picker');

            var to_$input = $('#to').pickadate({

                    formatSubmit: 'dd-mm-yyyy',
                    hiddenSuffix: '_date'

                }),
                to_picker = to_$input.pickadate('picker');


            function checkAvailability() {

                if (from_picker.get('select') && to_picker.get('select')) {

                    checkRequestPlaced();
                    var product_id = '{{ $product->id }}';
                    var csrf = '{{ csrf_token() }}';
                    var from_date = $('[name="from_date"]').val().split("-").reverse().join("-");
                    var to_date = $('[name="to_date"]').val().split("-").reverse().join("-");
                    $.ajax({

                        type: 'POST',
                        url: '{{ route('check_availability') }}',
                        data: {_token: csrf, product_id: product_id, from_date: from_date, to_date: to_date},
                        dataType: 'JSON',
                        success: function (response) {


                            if (response.availability == '0') {

                                $('#availability_message').slideDown();

                            } else {

                                $('#availability_message').slideUp();

                            }

                        }

                    });

                }
                else {


                }

            }

            //js for disabling dates

            var yesterday = new Date((new Date()).valueOf() - 1000 * 60 * 60 * 24);
            var unavailable_dates;

            function setMinMax() {

                var disable_from = unavailable_dates;
                var disable_to = unavailable_dates;
                disable_from.sort(function (a, b) {
                    return b.from - a.from;
                });
                disable_to.sort(function (a, b) {
                    return a.to - b.to;
                });

                from_picker.on('set', function (event) {
                    if (event.select) {
                        checkAvailability();
                        $.each(disable_from, function (i, d) {
                            if (d.from.getTime() > from_picker.get('select').pick) {

                                to_picker.set('max', d.from);
                                return false;

                            }

                        });
                        to_picker.set('min', from_picker.get('select'));
                    }
                    else if ('clear' in event) {
                        to_picker.set('max', false);
                        to_picker.set('min', false);
                    }
                });

                to_picker.on('set', function (event) {
                    if (event.select) {
                        checkAvailability();
                        $.each(disable_to, function (i, d) {

                            if (d.to.getTime() < to_picker.get('select').pick) {

                                from_picker.set('min', d.to);
                                return false;

                            }

                        });
                        from_picker.set('max', to_picker.get('select'));
                    }
                    else if ('clear' in event) {
                        from_picker.set('max', false);
                        from_picker.set('min', false);
                    }
                });

            }

            function getUnavailableDates() {

                var product_id = '{{ $product->id }}';
                var csrf = '{{ csrf_token() }}';
                $.ajax({

                    type: 'POST',
                    url: '{{ route('get_unavailable_dates') }}',
                    data: {_token: csrf, product_id: product_id},
                    dataType: 'JSON',
                    success: function (response) {

                        var disable = [];
                        unavailable_dates = [];

                        $.each(response, function (i, d) {

                            disable.push({from: new Date(d.from_date), to: new Date(d.to_date)});
                            unavailable_dates.push({from: new Date(d.from_date), to: new Date(d.to_date)});

                        });

                        disable.push({from: [0, 0, 0], to: yesterday});
                        from_picker.set('disable', disable);
                        to_picker.set('disable', disable);
                        setMinMax();

                    }

                });

            }

            getUnavailableDates();

            //js for checking for request already placed
            function checkRequestPlaced() {

                if (logged_in) {

                    var contact_owner_form = $('#contact_owner_form');
                    var contact_button = $('#contact_owner');
                    contact_owner_form.append('<input name="product_id" type="hidden" value="{{ $product->id }}">');
                    var formData = contact_owner_form.serialize();
                    contact_button.addClass('disabled');
                    contact_button.html('Checking');

                    $.ajax({
                        type: 'POST',
                        url: '{{ route('check_request_placed') }}',
                        data: formData,
                        success: function (returned_data) {

                            if (returned_data.placed) {

                                contact_button.html('Request placed');
                                placed = true;

                            } else {

                                contact_button.removeClass('disabled');
                                contact_button.html('Place a request');
                                placed = false;

                            }

                        }
                    });

                }

            }


        });

    </script>
@endsection