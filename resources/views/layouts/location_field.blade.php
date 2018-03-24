@section('location_field')
    <div class="col-12">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6" style="padding-top: 1em; padding-bottom: 1em;">
                    <input placeholder="&#xF041; &nbsp; Location" id="location_field" type="text" value="">
                </div>
                <form id="location_form" class="hidden">
                    <input id="lat_field" type="text" name="lat" hidden>
                    <input id="lng_field" type="text" name="lng" hidden>
                </form>
            </div>
        </div>
    </div>
@endsection