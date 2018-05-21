@section('footer')
    <footer class="footer-3 text-center-xs space--xs bg--dark ">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h3 style="font-weight: 900; margin-bottom: 0; color: #5f5f5f; display: inline-block;">
                        rentinghood
                    </h3>
                    <p class="type--fine-print">
                        Rent anything, right from your neighbourhood
                    </p>
                    <a class="type--fine-print" style="margin-left: 0;" href="{{ route('contact') }}">
                        Have something to say?
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="text-right text-center-xs">
                        <ul class="social-list list-inline list--hover">
                            <li class="list-inline-item">
                                <a target="_blank" href="https://www.linkedin.com/company/rentinghood">
                                    <i class="socicon socicon-linkedin icon icon--xs"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a target="_blank" href="https://www.facebook.com/rentinghood">
                                    <i class="socicon socicon-facebook icon icon--xs"></i>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a target="_blank" href="https://www.instagram.com/rentinghood">
                                    <i class="socicon socicon-instagram icon icon--xs"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="text-right text-center-xs">
                        <span class="type--fine-print">&copy;<span class="update-year"></span> RentingHood Inc.</span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <a class="back-to-top inner-link" href="#start" data-scroll-class="100vh:active">
        <i class="stack-interface stack-up-open-big"></i>
    </a>

@endsection
