@extends('layouts.vendor_dashboard')

@section('scripts')

    @parent
    <script src="{{ asset('admin/js/sweetalert2.all.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var productTemplate = $('#product-template').html();
        $('#product-template').remove();
        var base_url = '{{ \Illuminate\Support\Facades\URL::to('/') }}';

        function loadInventory() {
            var productsContainer = $('.mt-element-card.mt-element-overlay').children('.row');
            productsContainer.html('');
            $.ajax({
                url: base_url + '/vendor/inventory/show-all',
                type: 'GET',
                dataType: 'JSON',
                success: function (response) {
                    if(response.status === 'success') {
                        if(response.data.length === 0) {
                            swal(
                                "Empty Inventory",
                                "",
                                "error"
                            )
                        }
                        $.each(response.data, function (i, product) {
                            var productDiv = $(productTemplate);
                            productDiv.find('.mt-card-item').css('overflow', 'hidden');
                            productDiv.find('img').attr('src', base_url + '/img/uploads/products/small/' + product.image);
                            productDiv.find('h3.mt-card-name').html('<span style="white-space: nowrap;">' + product.name + '</span>');
                            productDiv.find('p.mt-card-desc').html(product.subcategory_id);
                            productDiv.find('a.edit').attr('href', base_url + '/a/products/' + product.id + '/edit');
                            productDiv.find('.md-checkbox').find('input').attr('id', 'checkbox' + product.id).attr('checked', product.availability === 1).on('change',  function () {
                                if($(this).is(":checked"))
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
            var csrf = '{{ csrf_token() }}';
            $.ajax({
                type: 'POST',
                url: '{{ route('update_availability') }}',
                dataType: 'JSON',
                data: {_token: csrf, product_id: id, availability: availability}
            });
        }

        loadInventory();
    </script>

@endsection

@section('content')

    <div class="page-content">
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
                    <div class="portlet-body">
                        <div class="mt-element-card mt-element-overlay">
                            <div class="row">
                                <div id="product-template">
                                    <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                        <div class="mt-card-item">
                                            <div class="mt-card-avatar mt-overlay-1">
                                                <img src="" />
                                                <div class="mt-overlay">
                                                    <ul class="mt-info">
                                                        <li>
                                                            <a class="edit btn default btn-outline" href="">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="mt-card-content">
                                                <h3 class="mt-card-name"></h3>
                                                <p class="mt-card-desc font-grey-mint"></p>
                                                <div class="md-checkbox-list" style="margin-left: 20px;">
                                                    <div class="md-checkbox" style="width: 100px;">
                                                        <input type="checkbox" class="md-check">
                                                        <label>
                                                            <span></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span> Availability </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>



@endsection