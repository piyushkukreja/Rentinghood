/*
 This file includes the JavaScript for finding location based on Pin-code
 Input: Pin-code, Callback functions (On geocode success and failure)
 */

var api_key = "AIzaSyCRlEvXSRnoYhHGMhrWihAujGP5OVu1OkQ";

function locate_area(pincode, callback_pass, callback_fail) {
    $.getJSON("https://maps.googleapis.com/maps/api/geocode/json?address=" + pincode + "&components=country:IN&key=" + api_key)
        .done(function (data) {
            if (data.status === "OK") {
                // Get Let & Long
                var area_lat = data.results[0].geometry.location.lat;
                var area_long = data.results[0].geometry.location.lng;
                if (area_lat !== '' && area_long !== '') {
                    $.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng=" + area_lat + "," + area_long + "&key=" + api_key)
                        .done(function (data) {
                            if (data.status === "OK") {
                                var country_name ="", state_name = "", city_name = "", city_name_2 = "", area_name = "";
                                var results = data.results[0].address_components;
                                $.each(results, function (index, val) {
                                    // Get Country
                                    if ($.inArray("country", val.types) !== -1)  {
                                        country_name = val.long_name;
                                    }
                                    // Get State
                                    if ($.inArray("administrative_area_level_1", val.types) !== -1)  {
                                        state_name = val.long_name;
                                    }
                                    // Get City
                                    if ($.inArray("administrative_area_level_2", val.types) !== -1)  {
                                        city_name_2 = val.long_name;
                                    }
                                    if ($.inArray("locality", val.types) !== -1)  {
                                        city_name = val.long_name;
                                    }
                                    // Get Area
                                    if ($.inArray("sublocality_level_1", val.types) !== -1)  {
                                        area_name = val.long_name;
                                    }
                                });

                                // Set values to fields
                                callback_pass(country_name, state_name, city_name, area_name);

                            } else {
                                callback_fail();
                            }
                        })
                        .fail(function (jqxhr, textStatus, error) {
                            callback_fail();
                        });
                }
            } else {
                callback_fail();
            }
        })
        .fail(function (jqxhr, textStatus, error) {
            callback_fail();
        });
}

//