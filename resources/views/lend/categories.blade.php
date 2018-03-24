@extends('layouts.app')
@extends('layouts.navbar')
@section('content')
    <style>
        #scroll_to_content {
            position: absolute;
            width: 3em;
            height: 3em;
            background: #fff;
            right: 1em;
            margin-left: -1.85em;
            border-radius: 50%;
            text-align: center;
            top: 1em;
            padding-top: 8px;
            box-shadow: 0 0 25px 0 rgba(0, 0, 0, 0.04);
            z-index: 98;
            border: 1px solid #ececec;
            transition: 0.2s ease-out;
            -webkit-transition: 0.2s ease-out;
            -moz-transition: 0.2s ease-out;
        }
        #scroll_to_content i {
            color: #03A9F4;
        }
        #scroll_to_content:hover {
            transform: translate3d(0, -5px, 0);
            -webkit-transform: translate3d(0, -5px, 0);
        }
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
        section.how_to_section {
            padding-top: 3em;
            padding-bottom: 3em;
        }
        @media (max-width: 577px) {
            .feature i + h4 {
                margin-top: 0;
            }
            .feature i {
                margin-bottom: 0;
            }
            h4 {
                margin-bottom: 0;
            }
            .masonry__item {
                margin-bottom: 0.3em;
            }
            section.how_to_section {
                padding-top: 1em;
                padding-bottom: 1em;
            }
        }
    </style>
    <div class="main-container">
        <section class="title_section text-center bg--secondary">
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
        <section class="text-center how_to_section">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="feature feature-3 boxed boxed--sm boxed--border">
                            <a id="scroll_to_content" href="#content">
                                <i class="stack-interface stack-down-open-big"></i>
                            </a>
                            <i class="fa fa-sitemap fa-5x"></i>
                            <h4>Select a category</h4>
                            <p>
                                Choose a category to which your product belongs to.
                            </p>
                        </div>
                        <!--end feature-->
                    </div>
                    <div class="col-md-4">
                        <div class="feature feature-3 boxed boxed--sm boxed--border">
                            <i class="fa fa-list-alt fa-5x"></i>
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
                        <div class="feature feature-3 boxed boxed--sm boxed--border">
                            <i class="fa fa-bell fa-5x"></i>
                            <h4>Get notified</h4>
                            <a id="content"></a>
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
        <section class="space--sm categories_section">
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