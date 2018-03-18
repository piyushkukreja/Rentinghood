@extends('layouts.app')
@extends('layouts.navbar')
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
    <div class="main-container">
        <section class="text-center bg--secondary" style="padding-bottom: 3em; padding-top: 3em;">
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
        <section class="space--sm">
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

            $('.category_link').on('click', function (e) {

                e.preventDefault();
                var name =  $(this).siblings('.category_name_info').html();
                var url = '{{ \Illuminate\Support\Facades\URL::to('/rent/category') }}/' + name;
                $(location).attr('href', url);

            });

            $('.category').find('img').each( function(){

                $( this ).attr( 'src', $( this ).attr( 'data-src' ) );

            });


        });
    </script>

@endsection