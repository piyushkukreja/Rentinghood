@extends('layouts.public')
@extends('layouts.public_parts.navbar_transparent')
@section('head')
    @parent
    <style type="text/css">
        @media (min-width: 767px) {
            #center-logo {
                white-space: nowrap;
            }
        }
    </style>
@endsection
@section('scripts')
    @parent
    {{-- Sweet Alert 2 Plugin--}}
    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
    <script>
        (function( $ ){
            $.fn.fitText = function( kompressor, options ) {
                // Setup options
                var compressor = kompressor || 1,
                    settings = $.extend({
                        'minFontSize' : Number.NEGATIVE_INFINITY,
                        'maxFontSize' : Number.POSITIVE_INFINITY
                    }, options);

                return this.each(function(){

                    // Store the object
                    var $this = $(this);

                    // Resizer() resizes items based on the object width divided by the compressor * 10
                    var resizer = function () {
                        $this.css('font-size', Math.max(Math.min($this.width() / (compressor*10), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)));
                    };

                    // Call once to set.
                    resizer();

                    // Call on resize. Opera debounces their resize by default.
                    $(window).on('resize.fittext orientationchange.fittext', resizer);

                });
            };
        })( jQuery );

        @auth
        $('#center-logo').fitText(0.75);
        @else
        $('#center-logo').fitText(0.6);
        @endauth

        $(document).ready(function () {

            var backgroundImage = document.getElementById('home-background-image');
            if(!backgroundImage.complete) {
                $(backgroundImage).on('load', function () {
                    $('#pre-loader').fadeOut('slow', function () {
                        $(this).remove();
                    });
                });
            } else {
                setTimeout(function () {
                    $('#pre-loader').fadeOut('slow', function () {
                        $(this).remove();
                    });
                }, 1000);
            }

            $('.hiring_div').on('click', function () {
                window.location.href = '{{ route('careers') }}';
            });

            @auth
            function getMessageCount() {
                $.ajax({
                    url: '{{ route('get_message_count') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {
                        if (parseInt(response.requests_count) + parseInt(response.replies_count) != 0) {
                            swal({
                                title: 'You have new messages',
                                imageUrl: '{{ asset('img/message.gif') }}',
                                imageWidth: 300,
                                imageHeight: 225,
                                text: 'Visit the messages tab to view requests for your products and to answer them',
                                confirmButtonText: 'View Messages'
                            }).then((result) => {
                                if(result.value) {
                                    window.location.href = "{{ route('account', ['messages']) }}"
                                }
                            });
                        }
                    }
                });
            }
            getMessageCount();
            @endauth

            $('#scroll_to_content').click(function (e) {
                e.preventDefault();
                scrollToItem($($(this).attr('href')));
            });

            function scrollToItem(item) {
                $('html, body').animate({
                    scrollTop: item.offset().top
                }, 1000);
            }
        });
    </script>
@endsection
@section('content')
    <div class="main-container">
        <div class="hiring_div"><img src="{{ asset('img/hiring2.png') }}"></div>
        <section class="cover height-90 imagebg text-center" data-overlay="5" id="home">
            <div class="background-image-holder">
                <img id="home-background-image" alt="background" src="{{ asset('img/contactleft.jpg') }}"/>
            </div>
            {{--<div id="left-rent"></div>
            <div id="right-lend"></div>--}}
            <div class="container pos-vertical-center">
                <div class="row">
                    <div class="col-md-8">
                        {{--<img alt="Image" class="unmarg--bottom" src="{{ asset('img/home/logo-brush.png') }}"/>--}}
                        @auth
                        <h1 id="center-logo" style="font-weight: 600; margin-bottom: 0;">Hello, {{ \Illuminate\Support\Facades\Auth::user()->first_name }} :)</h1>
                        @else
                        <h1 id="center-logo" style="font-weight: 600; margin-bottom: 0;">RentingHood</h1>
                        @endauth
                        <h3 style="font-weight: 600; font-family: Raleway, sans-serif;">
                            Rent anything, right from your Neighbourhood
                        </h3>
                        <a id="btn--rent" class="btn btn--primary btn--lg" href="{{ route('rent_categories') }}" data-tooltip="Let's save some money.">
                                <span class="btn__text">
                                    RENT
                                </span>
                        </a>
                        <a id="btn--lend" class="btn btn--primary btn--lg" href="{{ route('lend_categories') }}" data-tooltip="Let's make some money.">
                                <span class="btn__text">
                                    LEND
                                </span>
                        </a>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <div class="text-center">
                <a id="scroll_to_content" href="#content">
                    <i class="stack-interface stack-down-open-big"></i>
                </a>
            </div>
            <!--end of container-->
        </section>
        <a id="content"></a>
        <section class="text-center" id="about">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <h2>What we do?</h2>
                        <p class="lead">
                            It’s pretty simple! We connect the lenders with the renters to encourage the business of
                            renting products and commodities in the same neighbourhood.
                        </p>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        {{--<section class="text-center bg--secondary">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-lg-6">
                        <h2>The process is simple.</h2>
                        <div class="video-cover border--round">
                            <div class="background-image-holder">
                                <img alt="image" src="{{ asset('img/blog-3.jpg') }}" />
                            </div>
                            <div class="video-play-icon"></div>
                            <iframe data-src="https://www.youtube.com/embed/6p45ooZOOPo?autoplay=1" allowfullscreen="allowfullscreen"></iframe>
                        </div>
                        <!--end video cover-->
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>--}}
        @guest
            <section class="bg--primary unpad cta cta-2" id="video">
                <a href="{{ route('register') }}">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <h2>Become a RentingHood neighbour</h2>
                            </div>
                        </div>
                        <!--end of row-->
                    </div>
                    <!--end of container-->
                </a>
            </section>
        @endguest
    </div>
@endsection