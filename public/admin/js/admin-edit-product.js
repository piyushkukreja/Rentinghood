var ProductEditor = function () {

    var init = function (base_url, csrf, product) {

        var productForm = $('#product-form');

        function ucwords (str) {
            str = str.replace('_', ' ');
            return (str + '')
                .replace(/^(.)|\s+(.)/g, function ($1) {
                    return $1.toUpperCase()
                });
        }

        //js FOR ACCEPTING PRODUCT
        var acceptanceCheckbox = $('#accepted-checkbox');
        if(product.verified === 1)
            acceptanceCheckbox.prop('checked', true);
        acceptanceCheckbox.on('change', function () {
            $.ajax({
                url: base_url + '/products/' + product.id + '/update-state',
                type: 'POST',
                data: { _token: csrf, accepted: acceptanceCheckbox.is(':checked') },
                dataType: 'JSON'
            });
        });

        //js for SUBCATERGORIES
        var categorySelector = productForm.find($('#category_id'));

        function changeSubcategories() {

            var subcategorySelector = productForm.find($('#subcategory_id'));
            subcategorySelector.empty();
            var category_id = categorySelector.val();

            subcategorySelector.empty();
            subcategorySelector.append('<option value="" selected disabled>Select a subcategory</option>');

            if (category_id != '') {
                $.ajax({
                    type: 'GET',
                    url: base_url + '/categories/' + category_id + '/get-subcategories',
                    dataType: 'JSON',
                    success: function (returned_data) {
                        $.each(returned_data, function (i, d) {
                            subcategorySelector.append('<option value="' + d.id + '">' + ucwords(d.name) + '</option>');
                        });
                    }
                });
            }
        }

        categorySelector.on('change', function () {
            changeSubcategories();
        });

        //js for ADDRESS
        var defaultBounds = new google.maps.LatLngBounds(
            new google.maps.LatLng(19.296441, 72.9864994),
            new google.maps.LatLng(18.8465126, 72.9042434)
        );

        $("#address").geocomplete({
            location: product.address,
            details: "#product_latlng",
            bounds: defaultBounds
        }).bind("geocode:result", function (event, result) {
            console.log('Product :', result);
        });

        //js for DURATION AND RATES
        function changeRequiredStates(form_container) {
            var rate1 = form_container.find('input[name="rate_1"]');
            var rate2 = form_container.find('input[name="rate_2"]');
            var rate3 = form_container.find('input[name="rate_3"]');
            switch (form_container.find('select[name="duration"]').val()) {
                case '0' :
                    rate1.prop('required', true).parent().slideDown();
                    rate2.prop('required', false).parent().slideUp();
                    rate3.prop('required', false).parent().slideUp();
                    break;
                case '1' :
                    rate1.prop('required', true).parent().slideDown();
                    rate2.prop('required', true).parent().slideDown();
                    rate3.prop('required', false).parent().slideUp();
                    break;
                case '2' :
                    rate1.prop('required', true).parent().slideDown();
                    rate2.prop('required', true).parent().slideDown();
                    rate3.prop('required', true).parent().slideDown();
                    break;
            }
        }

        changeRequiredStates(productForm);
        $(productForm).find('select[name="duration"]').on('change', function () {
            changeRequiredStates(productForm);
        });

        //js for IMAGES
        $('input[type="radio"][name="product_thumbnail"]').change(function() {
            var radioInput = this;
            $.ajax({
                url: base_url + '/products/' + product.id + '/update-image',
                type: 'POST',
                dataType: 'JSON',
                data: { _token: csrf, file_id: radioInput.value }
            });
        });

        $('a.remove').on('click', function () {
            var link = $(this);
            $.ajax({
                url: base_url + '/products/' + product.id + '/remove-image',
                type: 'POST',
                dataType: 'JSON',
                data: { _token: csrf, picture_id: parseInt(link.parents('tr').find('td.product-picture-id').html()) },
                success: function (response) {
                    if(response.status === 'failed') {
                        swal(
                            "Error",
                            response.message,
                            "error"
                        )
                    } else {
                        link.parents('tr').hide();
                    }
                }
            });
        });
    };
    return {
        //main function to initiate the module
        init: function (base_url, csrf, product) {
            init(base_url, csrf, product);
        }
    };

}();