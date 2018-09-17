@extends('layouts.public')
@extends('layouts.public_parts.navbar_blue')
@section('head')
    @parent
    <style type="text/css">
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
        @media (min-width: 768px) {
            .picker__weekday { padding: 0.6em; }
        }
        @media (min-width: 992px) {
            .picker__weekday { padding: 0.9em; }
        }
        @media (max-width: 990px) {
            #product_hr {
                margin-top: 0;
                margin-bottom: 0.5em;
            }
        }
        #contact-owner {
            font-size: 1em;
            margin-top: 0.5em;
            margin-bottom: 5em;
        }
        #register-modal .modal-content {
            max-width: 600px;
        }
    </style>
@endsection
@section('scripts')
    @parent
    {{-- DATEPICKER --}}
    <script src="{{ asset('js/datepicker.js') }}" type="text/javascript"></script>
    {{-- SWEETALERT2 --}}
    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            //Buttons
            var contactOwnerButton = $('#contact-owner');
            var socialLoginButton = $('.social-login');

            //Forms
            var loginForm = $('#login-form');
            var registerForm = $('#register-form');
            var contactOwnerForm = $('#contact-owner-form');

            //Modals and Triggers
            var loginModal = $('#login-modal');
            var loginModalTrigger = $('#login-modal-trigger');
            var registerModal = $('#register-modal');
            var registerModalTrigger = $('#register-modal-trigger');

            //Inputs
            var fromInput = $('#from');
            var toInput = $('#to');

            //js for Login Modal
            @guest
                var loggedIn = false;

                function makePostLoginUIChanges() {
                    window.location.href = encodeURI(window.location.href.replace(/[\?#].*|$/, '?from_date=' + $('[name="from_date"]').val() + '&to_date=' + $('[name="to_date"]').val()));
                }

                loginForm.on('submit', function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    $('#login-error').remove();
                    $.ajax({
                        type: 'POST',
                        url: loginForm.attr('action'),
                        data: loginForm.serialize(),
                        success: function (returned_data) {
                            loginModal.find('.modal-close-cross').trigger('click');
                            makePostLoginUIChanges();
                            setTimeout(function () {
                                contactOwnerButton.trigger('click');
                            }, 1000);
                        },
                        error: function (xhr, status, error) {
                            var errors = JSON.parse(xhr.responseText);
                            var emailError = errors["errors"]["email"][0];
                            loginForm.find('#email').after('<span id="login-error" class="color--error">' + emailError + '</span>');
                        }
                    });
                });

                //js for Register Modal
                $('#register-link').on('click', function (event) {
                    event.preventDefault();
                    loginModal.find('.modal-close').trigger('click');
                    registerModalTrigger.trigger('click');
                });

                registerForm.submit(function (event) {
                    event.preventDefault();
                    event.stopPropagation();
                    $('.register-error').slideUp(function () {
                        $(this).remove();
                    });
                    $.ajax({
                        type: 'POST',
                        url: registerForm.attr('action'),
                        data: registerForm.serialize(),
                        success: function (returned_data) {
                            registerModal.find('.modal-close-cross').trigger('click');
                            makePostLoginUIChanges();
                        },
                        error: function (xhr, status, error) {
                            var errors = JSON.parse(xhr.responseText);
                            errors = errors["errors"];
                            for (var key in errors) {
                                if(errors.hasOwnProperty(key))
                                    registerForm.find('[name="' + key + '"]').after('<span class="register-error color--error">' + errors[key][0] + '</span>');
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
                var loggedIn = true;
            @endguest

            //js for Placing a request
            var placed = false;
            contactOwnerForm.submit(function (event) {
                event.preventDefault();
                event.stopPropagation();
                if (!placed) {
                    $('#from_error').remove();
                    $('#to_error').remove();
                    var errors = false;
                    if (fromInput.val() == '') {
                        fromInput.after('<span id="from_error" style="display: none;" class="color--error">Please select a from date</span>');
                        $('#from_error').fadeIn();
                        errors = true;
                    }
                    if (toInput.val() == '') {
                        toInput.after('<span id="to_error" style="display: none;" class="color--error">Please select a to date</span>');
                        $('#to_error').fadeIn();
                        errors = true;
                    }
                    if (!errors) {
                        if (!loggedIn) {
                            loginModalTrigger.trigger('click');
                            return;
                        }
                        contactOwnerButton.html('Placing your request').addClass('disabled');

                        $.ajax({
                            type: 'POST',
                            url: contactOwnerForm.attr('action'),
                            data: contactOwnerForm.serialize(),
                            success: function (returned_data) {
                                if(returned_data.status === 'success') {
                                    contactOwnerButton.html('Request placed');
                                    swal({
                                        title: 'Your request has been placed!',
                                        text: returned_data.name + ', has received your request. If he approves the request he will get back to you shortly.',
                                        type: 'success'
                                    });
                                    placed = true;
                                }
                            },
                            error: function (response) {
                                contactOwnerButton.html('Place a request').removeClass('disabled');
                                var responseJSON = JSON.parse(response.responseText);
                                var redirectTo = encodeURIComponent(window.location.href.replace(/[\?#].*|$/, '?from_date=' + $('[name="from_date"]').val() + '&to_date=' + $('[name="to_date"]').val()));
                                if(responseJSON.message === 'mobile_auth')
                                    window.location.href = '{{ route('otp.form') }}?redirect_to=' + redirectTo;
                                else
                                    window.location.href = '{{ route('verification.notice') }}?redirect_to=' + redirectTo;
                            }
                        });
                    }
                }
            });

            //js for Datepicker
            fromInput.on('click', function () {
                scrollToItem($(this));
            });
            toInput.on('click', function () {
                scrollToItem($(this));
            });

            function scrollToItem(item) {
                $('html, body').animate({
                    scrollTop: item.offset().top
                }, 1000);
            }

            var fromPicker = fromInput.pickadate({
                    formatSubmit: 'dd-mm-yyyy',
                    hiddenSuffix: '_date'
                }).pickadate('picker');

            var toPicker = toInput.pickadate({
                    formatSubmit: 'dd-mm-yyyy',
                    hiddenSuffix: '_date'
                }).pickadate('picker');

            function checkAvailability() {
                if (fromPicker.get('select') && toPicker.get('select')) {
                    checkRequestPlaced();
                    var product_id = '{{ $product->id }}';
                    var csrf = '{{ csrf_token() }}';
                    var fromDate = $('[name="from_date"]').val().split("-").reverse().join("-");
                    var toDate = $('[name="to_date"]').val().split("-").reverse().join("-");
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('check_availability') }}',
                        data: {_token: csrf, product_id: product_id, from_date: fromDate, to_date: toDate},
                        dataType: 'JSON',
                        success: function (response) {
                            if (response.availability === 0)
                                $('#availability_message').slideDown();
                            else
                                $('#availability_message').slideUp();
                        }
                    });
                }
            }

            //js for disabling dates
            var yesterday = new Date((new Date()).valueOf() - 1000 * 60 * 60 * 24);
            var unavailableDates;

            function setMinMax() {
                var disableFrom = unavailableDates;
                var disableTo = unavailableDates;
                disableFrom.sort(function (a, b) {
                    return b.from - a.from;
                });
                disableTo.sort(function (a, b) {
                    return a.to - b.to;
                });

                fromPicker.on('set', function (event) {
                    if (event.select) {
                        checkAvailability();
                        $.each(disableFrom, function (i, d) {
                            if (d.from.getTime() > fromPicker.get('select').pick) {
                                toPicker.set('max', d.from);
                                return false;
                            }
                        });
                        toPicker.set('min', fromPicker.get('select'));
                    }
                    else if ('clear' in event) {
                        toPicker.set('max', false);
                        toPicker.set('min', false);
                    }
                });

                toPicker.on('set', function (event) {
                    if (event.select) {
                        checkAvailability();
                        $.each(disableTo, function (i, d) {
                            if (d.to.getTime() < toPicker.get('select').pick) {
                                fromPicker.set('min', d.to);
                                return false;
                            }
                        });
                        fromPicker.set('max', toPicker.get('select'));
                    }
                    else if ('clear' in event) {
                        fromPicker.set('max', false);
                        fromPicker.set('min', false);
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
                        unavailableDates = [];
                        $.each(response, function (i, d) {
                            disable.push({from: new Date(d.from_date), to: new Date(d.to_date)});
                            unavailableDates.push({from: new Date(d.from_date), to: new Date(d.to_date)});
                        });
                        disable.push({from: [0, 0, 0], to: yesterday});
                        fromPicker.set('disable', disable);
                        toPicker.set('disable', disable);
                        setMinMax();
                    }
                });
            }

            getUnavailableDates();

            //js for checking for request already placed
            function checkRequestPlaced() {
                if (loggedIn) {
                    contactOwnerButton.html('Checking...').addClass('disabled');
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('check_request_placed') }}',
                        data: contactOwnerForm.serialize(),
                        success: function (response) {
                            if (response.placed) {
                                contactOwnerButton.html('Request placed');
                                placed = true;
                            } else {
                                contactOwnerButton.html('Place a request').removeClass('disabled');
                                placed = false;
                            }
                        }
                    });
                }
            }

            socialLoginButton.on('click', function (event) {
                event.preventDefault();
                var redirectTo = encodeURIComponent(window.location.href.replace(/[\?#].*|$/, '?from_date=' + $('[name="from_date"]').val() + '&to_date=' + $('[name="to_date"]').val()));
                window.location.href = $(this).attr('href') + '?post_login_url=' + redirectTo;
            });

            @if(isset($_GET['from_date']) && isset($_GET['to_date']))
                fromPicker.set('select', '{{ $_GET['from_date'] }}');
                toPicker.set('select', '{{ $_GET['to_date'] }}');
            @endif
        });
    </script>
@endsection
@section('content')
    <div class="main-container">
        <section class="bg--secondary" style="padding-bottom: 3em; padding-top: 3em;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 style="margin-bottom: 0.2em;">{{ ucwords($product->name) }}</h1>
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
                            @foreach(explode('<br>', $product->description) as $line)
                                {{ $line }}
                                <br />
                            @endforeach
                        </p>
                        <div class="row">
                            @if( $product->rate_1 != 0 )
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
                            @endif
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
                        <form id="contact-owner-form" method="post" action="{{ route('contact_owner') }}">
                            {{ csrf_field() }}
                            <input name="product_id" type="hidden" value="{{ $product->id }}">
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
                                <button id="contact-owner" type="submit" class="btn btn--primary">
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
            <a id="login-modal-trigger" href="#" class="modal-trigger hidden">Login</a>
            <div id="login-modal" class="modal-container">
                <div class="modal-content section-modal imagebg" data-overlay="7">
                    <div class="background-image-holder">
                        <img alt="background" src="{{ asset('img/login-bg.jpg') }}" />
                    </div>
                    <section class="unpad ">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="boxed bg--none boxed--lg text-center feature">
                                        <div class="modal-close modal-close-cross"></div>
                                        <div class="text-block">
                                            <h3>Login to Your Account</h3>
                                            <p>
                                                Welcome back, sign in with your existing account credentials.
                                            </p>
                                        </div>
                                        <div class="feature__body">
                                            <form id="login-form" method="POST" action="{{ route('login') }}">
                                                {{ csrf_field() }}
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a class="btn block btn--icon bg--googleplus type--uppercase social-login"
                                                           href="{{ route('social-login', ['google']) }}">
                                                            <span class="btn__text">
                                                                <i class="socicon-google"></i>
                                                                Login with Google +
                                                            </span>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <hr style="margin-bottom: 0">
                                                    </div>
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
                                                Don't have an account yet? <a id="register-link" href="#">Create account</a>
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
            <a id="register-modal-trigger" class="btn modal-trigger hidden" href="#"></a>
            <div id="register-modal" class="modal-container">
                <div class="modal-content section-modal imagebg" data-overlay="7">
                    <div class="background-image-holder">
                        <img alt="background" src="{{ asset('img/register-bg.jpg') }}" />
                    </div>
                    <section class="unpad ">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-12">
                                    <div class="boxed bg--none boxed--lg text-center feature">
                                        <div class="modal-close modal-close-cross"></div>
                                        <div class="text-block">
                                            <h1>Become a RentingHood neighbour</h1>
                                        </div>
                                        <div class="feature__body">
                                            <form id="register-form" method="POST" action="{{ route('register') }}">
                                                {{ csrf_field() }}
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a class="btn block btn--icon bg--googleplus type--uppercase social-login"
                                                           href="{{ route('social-login', ['google']) }}">
                                                    <span class="btn__text">
                                                        <i class="socicon-google"></i>
                                                        Signup with Google +
                                                    </span>
                                                        </a>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <hr style="margin-bottom: 0">
                                                    </div>
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
                </div>
            </div>
        </div>
    @endguest

    <!-- CONTACT OWNER MODAL -->
    <a id="scroll_to_end" href="#end" class="hidden"></a>
@endsection