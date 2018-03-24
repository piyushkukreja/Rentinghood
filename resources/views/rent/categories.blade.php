@extends('layouts.app')
@extends('layouts.navbar')
@extends('layouts.location_field')
@section('content')
    <style>
        .category_image {
            padding-right: 1em;
            padding-left: 1em;
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

    <!-- LOCATION MODAL -->
    <div id="location_modal_template" class="hidden">
        <div class="modal-instance">
            <div id="location_modal" class="modal-container">
                <div class="modal-content">
                    <div class="boxed boxed--lg">
                        <h2>Tell us your location</h2>
                        <hr class="short">
                        <p class="lead">
                            Your location will help us show products which are near you.
                        </p>
                        <div class="text-center">
                            <a class="btn btn--lg btn--primary type--uppercase" href="#">
                                <span class="btn__text">Update</span>
                            </a>
                        </div>
                    </div>
                    <div class="modal-close modal-close-cross"></div>
                </div>
            </div>
            <a href="#" id="location_modal_trigger" class="modal-trigger"></a>
        </div>
    </div>

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
                                                <img alt="{{ $category->name }}" class="img-fluid category_image" data-src="{{ asset('img/categories') }}/{{ $category->name }}.png" src="{{ asset('img/loading.gif') }}"/>
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

    <script>
        $(document).ready(function () {

            //js for LOCATION FIELD
            function saveLocation() {
                var csrf_token = '{{ csrf_token() }}';
                var location = $('#location_field').val();
                var lat = $('#lat_field').val();
                var lng = $('#lng_field').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('save_location') }}',
                    data: { _token: csrf_token, location: location, lat: lat, lng: lng}
                });
            }

                    @if(Session::has('location'))

            var location_field = '{{ Session::get('location') }}';

            $('#location_field').geocomplete({
                location: location_field,
                details: "#location_form",
            }).bind("geocode:result", function (event, result) {
                saveLocation();
            });

            @else

            $('#location_field').geocomplete({ details: "#location_form" }).bind("geocode:result", function (event, result) {
                saveLocation();
            });

            @endif

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
                        if(response.message == 'success') {
                            var name =  clicked_category.siblings('.category_name_info').html();
                            var url = '{{ \Illuminate\Support\Facades\URL::to('/rent/category') }}/' + name;
                            $(location).attr('href', url);
                        } else {
                            $('.modal-trigger').trigger('click');
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