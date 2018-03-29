@section('location_field')
    <div class="col-12">
        <div class="container">
            <div class="row">
                <div class="col-12 offset-md-3 col-md-6" style="padding-top: 1em; padding-bottom: 1em;">
                    <input placeholder="&#xF041; &nbsp; Area or Locality" id="location_field" type="text" value="" autocomplete="off">
                </div>
                <form id="location_form" class="hidden">
                    <input id="lat_field" type="text" name="lat" hidden autocomplete="off">
                    <input id="lng_field" type="text" name="lng" hidden autocomplete="off">
                </form>
            </div>
        </div>
    </div>
@endsection