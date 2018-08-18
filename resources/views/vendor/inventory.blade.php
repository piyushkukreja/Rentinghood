@extends('layouts.vendor_dashboard')

@section('scripts')

    @parent
    <script src="{{ asset('admin/js/sweetalert2.all.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/vendor-inventory.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        var base_url = '{{ \Illuminate\Support\Facades\URL::to('/') }}';
        var csrf = '{{ csrf_token() }}';
        jQuery(document).ready(function() {
            InventoryLoader.init(base_url, csrf);
        });
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