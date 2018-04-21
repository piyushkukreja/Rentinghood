@extends('layouts.app')
@extends('layouts.navbar')
@section('content')

    <div class="main-container">
        <section class="title_section text-center bg--secondary">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-lg-8">
                        <h1>Get In Touch</h1>
                        <p class="lead">
                            Have a query? Just fill out a few fields and we will get back to you!
                        </p>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
        <section class="text-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-7">
                        <form id="contact-form" class="text-left row">
                            <div class="col-md-12">
                                <input type="text" id="name" name="name" placeholder="Your Name"/>
                            </div>
                            <div class="col-md-6">
                                <input type="email" id="email" name="email" placeholder="E-mail Address"/>
                            </div>
                            <div class="col-md-6">
                                <input type="number" id="contact" name="contact" placeholder="Phone Number"/>
                            </div>
                            <div class="col-md-12">
                                <textarea rows="6" id="message" name="message" placeholder="Message"></textarea>
                            </div>
                            <div class="col-md-5 col-lg-4">
                                <button type="submit" class="btn btn--primary type--uppercase">Send Enquiry</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!--end of row-->
            </div>
        <!--end of container-->
        </section>
    </div>

    {{-- Sweet Alert 2 Plugin--}}
    <script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var $form = $('form#contact-form'),
                url = 'https://script.google.com/macros/s/AKfycbzk0jTwyTurfi0Fpa5wNRU2lZIqQqe63qXpge2ylA/exec';

            var months = ["January","February","March","April","May","June","July","August","September","October","November","December"];
            $form.on('submit', function(e) {
                e.preventDefault();
                var currentTime = new Date();
                var dateTime = currentTime.getHours() + ':' + currentTime.getMinutes() + ', ' + currentTime.getDate() + ' ' + months[currentTime.getMonth()] + ', ' + currentTime.getFullYear();
                $('#contact-form').append('<input type="hidden" id="time" name="time" value="' + dateTime + '">');

                $.ajax({
                    url: url,
                    method: "GET",
                    dataType: "JSON",
                    data: $form.serialize(),
                    success: function (response) {
                        swal({
                            title: 'Your request has been placed!',
                            text: 'We have got your message. We will get back to you shortly.',
                            type: 'success'
                        });
                        console.log(response);
                    }
                });
            });
        });
    </script>

@endsection