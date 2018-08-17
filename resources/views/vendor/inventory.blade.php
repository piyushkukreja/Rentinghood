@extends('layouts.vendor_dashboard')

@section('scripts')

    @parent
    <script src="{{ asset('admin/js/sweetalert2.all.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/new-orders-datatables.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var base_url = '{{ \Illuminate\Support\Facades\URL::to('/') }}';
        jQuery(document).ready(function() {
            var csrf = '{{ csrf_token() }}';
            TableDatatablesResponsive.init(base_url, csrf);
        });

        /*function acceptTransaction() {
            $.ajax({
                url:"url('vendor/products/new-orders/accept{id})",
                type:"POST",
                dataType:"JSON",
                dataSrc: function( response ) {
                    var json = response.data;
                    var return_data = [];
                    var transaction = $('#accept',function () {
                        acceptOrder();
                    });

                }
            });
        }*/


        function updateAvailability(id, availability) {

            var csrf = '{{ csrf_token() }}';
            $.ajax({

                type: 'POST',
                url: '{{ route('update_availability') }}',
                dataType: 'JSON',
                data: {_token: csrf, product_id: id, availability: availability},
                success: function () {
                    console.log('success');
                }
            });

        }





        function loadInventory() {

            var inventory_div = $('#account-inventory');
            var inventory_form = inventory_div.find('#inventory_form').html();
            inventory_div.html('');
            inventory_div.append('<div id="inventory_form" style="display: none;">' + inventory_form + '</div>');
            swal({title: 'Preparing your inventory..'});
            swal.showLoading();
            $.ajax({

                type: 'GET',
                url: '{{ route('get_inventory') }}',
                dataType: 'JSON',
                success: function (returned_data) {

                    if (returned_data.length == 0) {
                        inventory_div.html('<div class="row align-items-center" style="height: 400px;">' +
                            '<div class="col-12 text-center h5">You havent uploaded any products :(</div>' +
                            '</div>' +
                            '</div>');
                    }
                    else {
                        inventory_div.append('<div id="inventory_row" class="row">' +
                            '<div class="col-md-12">' +
                            '<div class="masonry">' +
                            '<div class="masonry__container masonry--active row"></div>' +
                            '</div>' +
                            '</div>' +
                            '</div>');
                        var masonry_container = inventory_div.find('.masonry__container');
                        var loading_url = '{{ asset('img/loading.svg') }}';
                        $.each(returned_data, function (i, d) {

                            var image = '{{ asset('img/uploads/products/small') }}/' + d.image;
                            var product_link = '{{ URL::to('account/inventory') }}' + '/' + d.id;
                            var product = $('<div class="masonry__item col-6 col-lg-4">' +
                                '   <div class="product">' +
                                '       <span class="product_id_info hidden">' + d.name + '</span>' +
                                '       <a class="inventory_product_link" href="' + product_link + '">' +
                                '           <img class="img-fluid" style="border-radius: 5px;" alt="Image" id="" data-src="' + image + '" src="' + loading_url + '"/>' +
                                '       </a>' +
                                '       <a class="block inventory_product_link" href="' + product_link + '">' +
                                '           <div class="text-center"><h5>' + d.name + '</h5></div>' +
                                '       </a>' +
                                '       <form>' +
                                '       <div class="col-md-12">' +
                                '           <div class="input-checkbox input-checkbox--switch">' +
                                '               <input type="checkbox" ' + ((d.availability == '1')? 'checked ':'') + 'name="public-profile" id="checkbox-' + i + '">' +
                                '               <label for="checkbox-' + i + '"></label>' +
                                '           </div>' +
                                '           <span>Availability</span>' +
                                '       </div>' +
                                '       </form>' +
                                '   </div>' +
                                '</div>');
                            masonry_container.append(product);
                            product.find('input[type="checkbox"]').on('change', function () {

                                if(product.find('input[type="checkbox"]').is(":checked"))
                                    updateAvailability(d.id, 1);
                                else
                                    updateAvailability(d.id, 0);

                            });
                            product.find('a.inventory_product_link').on('click', function (e) {

                                swal({title: 'Getting post details'});
                                swal.showLoading();
                                var inventory_form = $('#inventory_form');
                                e.preventDefault();
                                $.ajax({
                                    url: $(this).attr('href'),
                                    type: 'GET',
                                    dataType: 'JSON',
                                    success: function (response) {
                                        swal.close();
                                        if(response.message == 'success') {
                                            inventory_form.find('input[name="category_id"]').val(ucwords(response.category_name));
                                            inventory_form.find('input[name="subcategory_id"]').val(ucwords(response.subcategory_name));
                                            inventory_form.find('input[name="name"]').val(response.name);
                                            inventory_form.find('textarea[name="description"]').html(response.description);
                                            inventory_form.find('select[name="duration"]').find('option[value="' + response.duration + '"]').attr('selected', true);
                                            inventory_form.find('input[name="rate1"]').val(response.rate_1);
                                            inventory_form.find('input[name="rate2"]').val(response.rate_2);
                                            inventory_form.find('input[name="rate3"]').val(response.rate_3);
                                            inventory_form.find('form').append('<input type="hidden" name="id" value="' + response.id + '">');
                                            changeRequiredStates(inventory_form);
                                            $('#inventory_row').slideUp(function () {
                                                inventory_form.slideDown();
                                            });
                                            inventory_form.find('input[name="address"]').geocomplete({
                                                location: response.address,
                                                details: $('#inventory_product_latlng'),
                                                bounds: defaultBounds
                                            });
                                        }
                                    }
                                });
                            });
                        });
                    }
                    $('.product').find('img').each(function () {
                        $(this).attr('src', $(this).attr('data-src'));
                    });
                    swal.close();
                }
            });
        }








        function rejectTransaction(id) {
            swal({
                title: 'Are you sure you want to delete this product?',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                allowEnterKey: false,
                confirmButtonClass: 'btn btn-warning',
                cancelButtonClass: 'btn btn-info',
            }).then((result) => {
                if (result.value) {
                var csrf = '{{ csrf_token() }}';
                var deleteForm = $('<form action="' + base_url + '/vendor/products/' + id + '" method="POST">' +
                    '<input type="hidden" name="_method" value="DELETE">' +
                    '<input type="hidden" name="_token" value=' + csrf + '>' +
                    '</form>');
                $('body').append(deleteForm);
                deleteForm.submit();
            }
        });
        }
    </script>
@endsection

@section('content')



    <style>
        #alert .fa-close {
            position: absolute;
            right: 0;
            top: 20px;
            margin-right: 15px;
            cursor: pointer;
        }
        #alert {
            font-size: 1.2em;
        }
        .menu-vertical li:not(:hover):not(.dropdown--active) {
            opacity: 1;
        }
        .message i.fa-check-circle, .message i.fa-times-circle {
            font-size: 2em;
            float: right;
        }
        .message .fa-check-circle {
            color: #4ebf56;
        }
        .message .fa-times-circle {
            color: #e23636;
        }
        .loading {
            background: url({{ asset('img/loading.svg') }}) center no-repeat;
            color: transparent;
        }
        .loading * {
            color: transparent;
        }
        .account-tab {
            min-height: 500px;
        }
        @media (max-width: 767px) {
            .boxed div[class*='col-']:not(.boxed).tab-container {
                padding-left: 15px;
                padding-right: 15px;
            }
            .tabs li:not(:last-child) {
                border-bottom: 1px solid #ECECEC;
                border-right: 1px solid #ECECEC;
            }
            .tabs li {
                display: inline-block;
            }
        }
        #account-messages h4, #account-messages p {
            margin-bottom: 0.5em;
        }
        #account-messages .boxed {
            padding: 1.2em;
            margin-bottom: 0.7em;
            border-width: 1.5px;
        }
        .badge-count {
            line-height: 22px;
            display: inline-block;
            text-align: center;
            background-color: #3b5998;
            width: 1.8em;
            height: 22px;
            color: white;
            border-radius: 50%;
        }
        .account-tab {
            min-height: 100vh;
        }
    </style>




    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Products</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('vendor.inventory') }}">Inventory</a>
            </li>
        </ul>
        <!-- END PAGE BREADCRUMB -->
        <!-- SHOW FLASH CONTENT -->
        @if(Session::has('rejected'))
            <p class="alert alert-warning">{{ session('rejected') }}</p>
        @endif
        @if(Session::has('success'))
            <p class="alert alert-success">{{ session('success') }}</p>
    @endif
    <!-- END FLASH CONTENT -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN TABLE PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <h4 style="margin: 0;">
                                <i class="icon-bag font-dark"></i>
                                <span class="caption-subject bold uppercase"> Inventory</span>
                            </h4>
                        </div>
                    </div>
                    <div id="account-inventory" class="account-tab">
                        <div class="col-12 text-center h5 well-lg">You havent uploaded any products :(</div>
                        <div id="inventory_form" style="display: none;">
                            <h4>Update your inventory</h4>
                            <form id="update-form" method="POST" action="{{ route('edit_product') }}">
                                {{ csrf_field() }}

                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Category</label>
                                        <input type="text" name="category_id" disabled>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Sub-Category:</label>
                                        <input type="text" name="subcategory_id" disabled>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Product Name</label>
                                        <input type="text" name="name" required disabled>
                                    </div>

                                    <div class="col-md-12">
                                        <label>Description</label>
                                        <textarea name="description" required></textarea>

                                    </div>

                                    <div class="col-md-12">
                                        <label>Duration</label>
                                        <div class="input-select">
                                            <select class="input-select" name="duration" required>
                                                <option value="0">Short Term</option>
                                                <option value="1">Weekly</option>
                                                <option value="2">Long Term</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12" style="margin-bottom: 0;">
                                        <label>Rates (in &#8377;)</label>
                                    </div>

                                    <div class="col-md-4">
                                        <input placeholder="Short Term" type="number" name="rate1">
                                    </div>

                                    <div class="col-md-4" style="display: none;">
                                        <input placeholder="Weekly" type="number" name="rate2">
                                    </div>

                                    <div class="col-md-4" style="display: none;">
                                        <input placeholder="Long Term" type="number" name="rate3">
                                    </div>

                                    <div class="col-md-12">
                                        <label>Address:</label>
                                        <input name="address" type="text" value="">
                                        <div id="inventory_product_latlng" class="hidden">
                                            <input name="lat" type="hidden" value="">
                                            <input name="lng" type="hidden" value="">
                                        </div>
                                    </div>

                                    <div class="col-lg-3 col-md-4">
                                        <input type="submit" id="update_post"
                                               class="btn btn--primary type--uppercase" name="submit_post"
                                               value="Post">
                                    </div>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>



@endsection