@extends('layouts.app')
@extends('layouts.navbar')
@section('content')
    <style>
        .category_image {
            padding : 1em;
        }
    </style>
    <div class="main-container">
        <section class="text-center bg--secondary" style="padding-bottom: 3em; padding-top: 3em;">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-lg-8">
                        <h1>Lend</h1>
                        <p class="lead">
                            Every unused belonging is an asset..
                        </p>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="text-center" style="padding-bottom: 3em; padding-top: 3em;">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature feature-3 boxed boxed--lg boxed--border">
                            <i class="icon icon--lg icon-Mail-3"></i>
                            <h4>Select a category</h4>
                            <p>
                                Choose a category to which your product belongs to.
                            </p>
                        </div>
                        <!--end feature-->
                    </div>
                    <div class="col-md-4">
                        <div class="feature feature-3 boxed boxed--lg boxed--border">
                            <i class="icon icon--lg icon-Code-Window"></i>
                            <h4>Fill out a form</h4>
                            <p>
                                Provide a few pictures,
                                quote the renting value,
                                a brief and crisp description and
                                availability (daily, weekly, monthly).
                            </p>
                        </div>
                        <!--end feature-->
                    </div>
                    <div class="col-md-4">
                        <div class="feature feature-3 boxed boxed--lg boxed--border">
                            <i class="icon icon--lg icon-Mail-3"></i>
                            <h4>Get notified</h4>
                            <p>
                                You’ll get a notification on your mobile number if any neighbour is interested in your product.
                            </p>
                        </div>
                        <!--end feature-->
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
                                            <a class="category_link" href="{{ URL::to('account/lend') }}/{{ $category->id }}">
                                                <img alt="{{ $category->name }}" class="img-fluid category_image" data-src="{{ asset('img/categories') }}/{{ $category->name }}.png" src="{{ asset('img/loading.gif') }}"/>
                                            </a>
                                            <a class="block category_link" href="{{ URL::to('account/lend') }}/{{ $category->id }}">
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

            $('.category').find('img').each( function(){

                $( this ).attr( 'src', $( this ).attr( 'data-src' ) );

            });

        });
    </script>

@endsection