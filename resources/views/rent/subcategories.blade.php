@extends('layouts.app')
@extends('layouts.navbar')
@extends('layouts.location_field')
@section('content')
    <style>
        .loading {

            background: url({{ asset('img/loading.gif') }}) center no-repeat;

        }
        ol.breadcrumbs, ol.breadcrumbs > li {
            margin-bottom: 0;
        }
        .account-tab {

            min-height: 400px;

        }
        @media (max-width: 767px) {
            .boxed div[class*='col-']:not(.boxed) {
                padding-left: 15px;
                padding-right: 15px;
            }
        }

        .subcategories_list li.active a {
            color: #0D47A1;
            opacity: 1;
        }
        .menu-vertical li:not(:hover):not(.dropdown--active) {
            opacity: 1;
        }

        .menu-vertical li:hover:not(.dropdown--active) {
            transform: translate(5px, 0);
        }


    </style>
    <div class="main-container">
        <section class="" style="padding-bottom: 2em; padding-top: 3em;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>{{ ucwords(str_replace('_', ' ', $category->name)) }}</h1>
                        <ol class="breadcrumbs">
                            <li>
                                <a href="{{ route('home') }}">Home</a>
                            </li>
                            <li>
                                <a href="{{ route('rent_categories') }}">Rent</a>
                            </li>
                            <li>{{ ucwords(str_replace('_', ' ', $category->name)) }}</li>
                        </ol>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="bg--secondary space--xs" style="min-height: 100vh;">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="boxed boxed--lg boxed--border">
                            <div class="text-block text-center">
                                <span class="h5">Subcategories</span>
                            </div>
                            <hr>
                            <div class="text-block">
                                <ul class="menu-vertical subcategories_list">
                                    <li class="active">
                                        <span class="subcategory_id_info hidden">0</span>
                                        <span class="subcategory_name_info hidden">all</span>
                                        <a href="#" id="all_link" class="subcategory_link"
                                           data-toggle-class=".account-tab:not(.hidden);hidden|#subcategory-all;hidden">All</a>
                                    </li>
                                    @foreach( $subcategories as $subcategory )
                                        <li>
                                            <span class="subcategory_id_info hidden">{{ $subcategory->id }}</span>
                                            <span class="subcategory_name_info hidden">{{ $subcategory->name }}</span>
                                            <a href="#" class="subcategory_link"
                                               data-toggle-class=".account-tab:not(.hidden);hidden|#subcategory-{{ $subcategory->name }};hidden">{{ ucwords(str_replace('_', ' ', $subcategory->name)) }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="boxed boxed--lg boxed--border">
                            <div id="subcategory-all" class="account-tab loading"></div>
                            @foreach( $subcategories as $subcategory )
                                <div id="subcategory-{{ $subcategory->name }}" class="hidden account-tab"></div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
    </div>

    <script>

        $(document).ready(function () {

            $('.subcategory_link').on('click', function () {

                $(this).parent().siblings('.active').removeClass('active');
                $(this).parent().addClass('active');
                var csrf_token = '{{ csrf_token() }}';
                var subcategory_id = $(this).siblings('.subcategory_id_info').html();
                var subcategory_name = $(this).siblings('.subcategory_name_info').html();
                var subcategory_div = $('#subcategory-' + subcategory_name);
                subcategory_div.html('');
                subcategory_div.addClass('loading');
                var category_id = {{ $category->id }};
                $.ajax({

                    type: 'POST',
                    url: '{{ route('get_subcategory_products') }}',
                    data: {_token: csrf_token, subcategory_id: subcategory_id, category_id: category_id},
                    dataType: 'JSON',
                    success: function (returned_data) {

                        subcategory_div.removeClass('loading');
                        if(returned_data.length == 0) {
                            subcategory_div.html('<div class="row align-items-center" style="height: 400px;">' +
                                '<div class="col-12 text-center h5">There are no products in this segment :(</div>' +
                                '</div>' +
                                '</div>');
                        }
                        else {
                            subcategory_div.html('<div class="row">' +
                                '<div class="col-md-12">' +
                                '<div class="masonry">' +
                                '<div class="masonry__container masonry--active row"></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>');
                            var masonry_container = subcategory_div.find('.masonry__container');
                            var loading_url = '{{ asset('img/loading.gif') }}';
                            $.each(returned_data, function (i, d) {

                                var image = '{{ asset('img/uploads/products/small') }}/' + d.image;
                                var product_link = '{{ \Illuminate\Support\Facades\URL::to('rent/product') }}' + '/' + d.id;
                                var product_html = '<div class="masonry__item col-6 col-lg-4">' +
                                    '<div class="product">' +
                                    '<span class="product_id_info hidden">' + d.name + '</span>' +
                                    '<a class="product_link" href="' + product_link + '">' +
                                    '<img class="img-fluid" style="border-radius: 5px;" alt="Image" id="" data-src="' + image + '" src="' + loading_url + '"/>' +
                                    '</a>' +
                                    '<a class="block product_link" href="' + product_link + '">' +
                                    '<div class="text-center"><h5>' + d.name + '</h5></div>' +
                                    '</a>' +
                                    '</div>' +
                                    '</div>';
                                masonry_container.append(product_html);

                            });
                        }
                        $('.product').find('img').each( function(){

                            $( this ).attr( 'src', $( this ).attr( 'data-src' ) );

                        } );

                    }

                });

            });

        });

        //js for LOCATION FIELD
        function saveLocation() {
            var csrf_token = '{{ csrf_token() }}';
            var location = $('#location_field').val();
            var lat = $('#lat_field').val();
            var lng = $('#lng_field').val();
            $.ajax({
                type: 'POST',
                url: '{{ route('save_location') }}',
                data: { _token: csrf_token, location: location, lat: lat, lng: lng},
                success: function () {
                    if($('.subcategories_list').find('li.active').find('a') == 0)
                        $('.subcategories_list').find('li.active').find('a').trigger('click');
                    else
                        $('#all_link').trigger('click');
                }
            });
        }

        var location_field = '{{ Session::get('location') }}';

        $('#location_field').geocomplete({
            location: location_field,
            details: "#location_form"
        }).bind("geocode:result", function (event, result) {
            saveLocation();
        });

    </script>

@endsection