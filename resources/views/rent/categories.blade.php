@extends('layouts.public')
@extends('layouts.public_parts.navbar_blue')
@extends('layouts.public_parts.location_field')
@section('head')
    @parent
    <style type="text/css">
        .category_image {
            padding-right: 1em;
            padding-left: 1em;
        }
        .swal2-container {
            z-index: 999;
        }
        .category {
            transition: transform 0.3s ease-out;
        }
        .category:hover {
            transform: translate(0,-5px);
        }
        @media (max-width: 577px) {
            h4 {
                margin-bottom: 0;
            }
            .masonry__item {
                margin-bottom: 0.3em;
            }
        }
    </style>
@endsection
@section('scripts')
    @parent
    {{-- Sweet Alert 2 Plugin--}}
    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js" type="text/javascript"></script>

    <script type="text/javascript">
        $(document).ready(function () {

            //js for LOCATION FIELD
            function saveLocation(url = null) {
                var csrf_token = '{{ csrf_token() }}';
                var location = $('#location_field').val();
                var lat = $('#lat_field').val();
                var lng = $('#lng_field').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('save_location') }}',
                    data: {_token: csrf_token, location: location, lat: lat, lng: lng},
                    success: function (response) {
                        console.log(response.message);
                    }
                });
                $.ajax({
                    url: '{{ route('get_location') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {
                        console.log(response.message);
                        if(url != null) {
                            window.location.href = url;
                        }
                    }
                });
            }

            $.ajax({
                url: '{{ route('get_location') }}',
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    if(response.message == 'success') {
                        $('#location_field').geocomplete({
                            location: response.location,
                            details: "#location_form"
                        }).bind("geocode:result", function (event, result) {
                            saveLocation();
                        });
                    } else {
                        $('#location_field').geocomplete({ details: "#location_form" }).bind("geocode:result", function (event, result) {
                            saveLocation();
                        });
                    }
                }
            });

            $('.modal-content').find('a').on('click', function (e) {
                e.preventDefault();
                $('.modal-container').removeClass('modal-active');
                $('#location_field').focus();
            });

            //js for CATEGORY CLICK
            $('.category_link').on('click', function (e) {

                var clicked_category = $(this);
                e.preventDefault();
                $.ajax({
                    url: '{{ route('check_location') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {
                        var name =  clicked_category.siblings('.category_name_info').html();
                        var url = '{{ \Illuminate\Support\Facades\URL::to('/rent/category') }}/' + name;
                        if(response.message == 'success') {
                            $(location).attr('href', url);
                        } else {
                            swal({
                                title: 'Which neighbourhood are you looking for?',
                                showConfirmButton: false,
                                imageUrl: '{{ asset('img/question.gif') }}',
                                imageWidth: 200,
                                imageHeight: 150,
                                html: '<div style="margin-bottom: 1em;">We don\'t want to show you results from Mars! :D</div>' +
                                '<div style="padding-top: 1em; padding-bottom: 3em;">' +
                                '<input placeholder="&#xF041; &nbsp; Area or Locality" id="swal_location_field" type="text" value="">' +
                                '</div>',
                                onBeforeOpen : function () {
                                    $('#swal_location_field').geocomplete({
                                        details: '#location_form'
                                    }).bind("geocode:result", function (event, result) {
                                        $('#location_field').val($('#swal_location_field').val());
                                        saveLocation(url);
                                        swal.close();
                                    });
                                    $('#swal_location_field').focus();
                                }
                            });
                        }
                    }
                });

            });

            //js for ASYNC IMAGE LOAD
            $('.category').find('img').each( function(){

                $( this ).attr( 'src', $( this ).attr( 'data-src' ) );

            });


        });
    </script>
@endsection
@section('content')
    <div class="main-container">
        <section class="title_section text-center bg--secondary">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-lg-8">
                        <h1>Rent</h1>
                        <p class="lead">
                            Why spend excessively when you can rent..
                        </p>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="space--sm categories_section">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="masonry">
                            <div class="masonry__container row">

                                @foreach($categories as $category)

                                    <div class="masonry__item col-6 col-lg-3">
                                        <div class="category">
                                            <span class="category_id_info hidden">{{ $category->id }}</span>
                                            <span class="category_name_info hidden">{{ $category->name }}</span>
                                            <a class="category_link" href="#">
                                                <img alt="{{ $category->name }}" class="img-fluid category_image" data-src="{{ asset('img/categories') }}/{{ $category->name }}.png" src="{{ asset('img/loading.svg') }}"/>
                                            </a>
                                            <a class="block category_link" href="#">
                                                <div class="text-center" style="margin-top: 1em;">
                                                    <h4>{{ ucwords(str_replace('_', ' ', $category->name)) }}</h4>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <!--end item-->
                                @endforeach

                            </div>
                            <!--end masonry container-->
                        </div>
                        <!--end masonry-->
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
    </div>
@endsection