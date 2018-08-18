@extends('layouts.public')
@extends('layouts.public_parts.navbar_blue')
@section('content')
<style>
    #contact-form {
        margin-top: 0;
    }
    #title-col {
        padding: 0;
    }
    #title-container {
        padding-bottom: 14em;
        cursor: default;
    }
    #title {
        color: #fff !important;
        font-weight: 500;
        font-size: 4em;
    }
    @media (min-width: 767px) {
        #email-container {
            padding-right: 0;
        }

    }
    @media (max-width: 767px) {
        #form-container {
            padding-top: 1.5em;
        }
        #contact-form > div {
            padding-right: 0;
        }
    }
</style>
    <div class="main-container">
        <section class="imageblock switchable feature-large height-100">
            <div class="imageblock__content col-md-6 pos-right hidden-xs">
                <div class="background-image-holder">
                    <img alt="image" src="{{ asset('img/contact/contact-bg.jpg') }}" />
                </div>
            </div>
            <div class="imageblock__content col-md-6 pos-right visible-xs">
                <div class="background-image-holder">
                    <img alt="image" src="{{ asset('img/contact/contact-bg-mobile.jpg') }}" />
                </div>
            </div>
            <div id="form-container" class="container pos-vertical-center" style="z-index: 5;">
                <div class="row">
                    <div class="col-md-5">
                        <p class="lead">
                            Have a query? Suggestion ?
                            <br>Or want to share any gossip from around the neighbourhood ?
                        </p>
                        <p class="lead">
                            We are all ears.
                        </p>
                        <hr class="short">
                        <form id="contact-form" class="text-left row">
                            <div class="col-md-12">
                                <input type="text" id="name" name="name" placeholder="Your Name" required/>
                            </div>
                            <div class="col-md-6" id="email-container">
                                <input type="email" id="email" name="email" placeholder="E-mail Address" required/>
                            </div>
                            <div class="col-md-6">
                                <input type="number" id="contact" name="contact" placeholder="Phone Number" required/>
                            </div>
                            <div class="col-md-12">
                                <textarea rows="6" id="message" name="message" placeholder="Message" required></textarea>
                            </div>
                            <div class="col-md-5 col-lg-4">
                                <button type="submit" class="btn btn--primary btn--lg">Send Message</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-1 h100">
                    </div>
                    <div id="title-col" class="col-md-6 hidden-xs d-flex align-items-center h100">
                        <div id="title-container">
                            <h1 id="title">Hello Neighbour :)</h1>
                        </div>
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

                swal({ title: 'Sending your message...'});
                swal.showLoading();

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
                            title: 'Message Sent ;)',
                            text: 'One of our Neighbour will revert you shortly.',
                            type: 'success'
                        });
                        console.log(response);
                    }
                });
            });
        });
    </script>

@endsection