@extends('layouts.public')
@include('layouts.public_parts.navbar_transparent')
@section('scripts')
    @parent
    <script type="text/javascript">
        $(document).ready(function () {
            $('.hiring_div').on('click', function () {
                window.location.href = '{{ route('careers') }}';
            });
        })
    </script>
@endsection
@section('content')
    <div class="main-container">
        <div class="hiring_div"><img src="{{ asset('img/hiring2.png') }}"></div>
        <section class="text-center imagebg space--lg" data-overlay="6">
            <div class="background-image-holder">
                <img alt="background" src="{{ asset('img/aboutus/home3.jpg') }}" />
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-lg-6">
                        <h1 class="about-heading">Hi, We're RentingHood</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="text-center bg--primary about-heading-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                        <h2 class="about-heading">Who we are?</h2>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="text-center" style="padding-top: 4em; padding-bottom: 4em;">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                        <p class="lead">
                            We are a bunch of neighbours, who believe happiness can not only be bought but could also be rented, especially when you need it only for the time being.<br/><br>
                            We are trying to convert the neighbours into renters by giving them a digital friendly background that felicitates renting atmosphere and ensures sharing of the products with ease and security.<br/><br/>
                            Pro Tip – You earn money out of it!
                        </p>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="text-center bg--primary about-heading-section">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
                        <h2 class="about-heading">Founding neighbours</h2>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="switchable feature-large" style="padding-top: 5em;">
            <div class="container">
                <div class="row justify-content-around">
                    <div class="col-md-5">
                        <img alt="Image" class="rounded-circle" src="{{ asset('img/aboutus/avatar-12.jpeg') }}" />
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="switchable__text">
                            <div class="text-block">
                                <h2>Bhushan Punjabi</h2>
                                <span>Founder &amp; CEO</span>
                            </div>
                            <p class="lead">
                                Prior to RentingHood, founder Bhushan Punjabi spent his days working as a distribution partner for a multinational company ‘XS’ and built a team of over 50 individuals and successfully launched the XS energy drink in the ever flourishing Indian Market. He had also been a part of Uber. But even when his days were busy, his ideas and cups of coffee kept him awake at night. And in this journey of being a nocturnal, Rentinghood took its foundation.
                            </p>
                            <ul class="social-list list-inline list--hover">
                                <li>
                                    <a href="mailto:punjabibhushan@gmail.com">
                                        <i class="socicon socicon-mail icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.linkedin.com/in/punjabibhushan/">
                                        <i class="socicon socicon-linkedin icon icon--xs"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="switchable switchable--switch feature-large">
            <div class="container">
                <div class="row justify-content-around">
                    <div class="col-md-5">
                        <img alt="Image" class="rounded-circle" src="{{ asset('img/aboutus/avatar-2.jpg') }}" />
                    </div>
                    <div class="col-md-6 col-lg-5">
                        <div class="switchable__text">
                            <div class="text-block">
                                <h2>Bharti Udasi</h2>
                                <span>Chief Creative Officer</span>
                            </div>
                            <p class="lead">
                                A graduate in Mass Media, Bharti is a very versatile person. When she isn’t working, she is usually found writing poems and performing them in poetry slams across Mumbai. She also does Graphology and is a part time Makeup Artist. With her varied interests, this feminist lady defines 'travelling' in books she reads, people she comes across and in her own journey of her life.
                            </p>
                            <ul class="social-list list-inline list--hover">
                                <li>
                                    <a href="mailto:bhartiudasi06@gmail.com">
                                        <i class="socicon socicon-mail icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.linkedin.com/in/bharti-udasi-45a199148/">
                                        <i class="socicon socicon-linkedin icon icon--xs"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="switchable feature-large">
            <div class="container">
                <div class="row justify-content-around">
                    <div class="col-md-5">
                        <img alt="Image" class="rounded-circle" src="{{ asset('img/aboutus/avatar-32.jpg') }}" />
                    </div>
                    <div class="col-md-8 col-lg-5">
                        <div class="switchable__text">
                            <div class="text-block">
                                <h2>Pankaj Ajwani</h2>
                                <span>Web Developer</span>
                            </div>
                            <p class="lead">
                                Being an undergrad hasn't stopped Pankaj from taking on multidisciplinary projects, which showcase his exemplary work. Having skills which span through the dimensions of website and application development, he is self-disciplined in multiple programming languages.
                            </p>
                            <ul class="social-list list-inline list--hover">
                                <li>
                                    <a target="_blank" href="mailto:pankaj.ajwani0409@gmail.com">
                                        <i class="socicon socicon-mail icon icon--xs"></i>
                                    </a>
                                </li>
                                <li>
                                    <a target="_blank" href="https://www.linkedin.com/in/pankaj-ajwani-0409/">
                                        <i class="socicon socicon-linkedin icon icon--xs"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
    </div>
@endsection