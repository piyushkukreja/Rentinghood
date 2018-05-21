@section('scripts')
    <script src="{{ asset('js/tether.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    {{--Places API and Geocomplete plugin --}}
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDtfAuKKrycjdbscKGGfbCg0R5udw3N73g&amp;libraries=places"></script>
    <script src="{{ asset('js/jquery.geocomplete.min.js') }}"></script>
    <script src="{{ asset('js/typed.min.js') }}"></script>
    <script src="{{ asset('js/isotope.min.js') }}"></script>
    <script src="{{ asset('js/granim.min.js') }}"></script>
    <script src="{{ asset('js/smooth-scroll.min.js') }}"></script>
    <script src="{{ asset('js/flickity.min.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
@endsection