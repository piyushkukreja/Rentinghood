var InventoryLoader = function () {

    var init = function (base_url, csrf) {

        var productTemplate = $('#product-template').html();
        $('#product-template').remove();

        function loadInventory() {
            var productsContainer = $('.mt-element-card.mt-element-overlay').children('.row');
            productsContainer.html('');
            $.ajax({
                url: base_url + '/inventory/show-all',
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    if (response.status === 'success') {
                        if (response.data.length === 0) {
                            swal(
                                "Empty Inventory",
                                "",
                                "error"
                            )
                        }
                        $.each(response.data, function (i, product) {
                            var productDiv = $(productTemplate);
                            productDiv.find('.mt-card-item').css('overflow', 'hidden');
                            productDiv.find('img').attr('src', '/img/uploads/products/small/' + product.image);
                            productDiv.find('h3.mt-card-name').html('<span style="white-space: nowrap;">' + product.name + '</span>');
                            productDiv.find('p.mt-card-desc').html(product.subcategory_id);
                            productDiv.find('a.edit').attr('href', base_url + '/products/' + product.id + '/edit');
                            productDiv.find('.md-checkbox').find('input').attr('id', 'checkbox' + product.id).attr('checked', product.availability === 1).on('change', function () {
                                if ($(this).is(":checked"))
                                    updateAvailability(product.id, 1);
                                else
                                    updateAvailability(product.id, 0);
                            });
                            productDiv.find('.md-checkbox').find('label').attr('for', 'checkbox' + product.id);
                            productsContainer.append(productDiv);
                        });
                    }
                }
            });
        }

        function updateAvailability(id, availability) {
            $.ajax({
                type: 'POST',
                url: base_url + '/products/update-availability',
                dataType: 'JSON',
                data: { _token: csrf, product_id: id, availability: availability }
            });
        }

        loadInventory();
    };
    return {
        //main function to initiate the module
        init: function (base_url, csrf) {
            init(base_url, csrf);
        }
    };

}();