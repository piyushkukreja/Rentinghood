@extends('layouts.admin_dashboard')

@section('scripts')

    @parent
    <script src="{{ asset('admin/js/sweetalert2.all.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/admin-new-products.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        @if(\Illuminate\Support\Facades\App::environment('local'))
            var base_url = '{{ route('home') }}/a';
        @else
            var base_url = '{{ route('admin.index') }}';
        @endif
        var csrf = '{{ csrf_token() }}';
        jQuery(document).ready(function() {
            TableDatatablesResponsive.init(base_url, csrf);
        });
    </script>

@endsection

@section('content')

    <div class="page-content">
        <!-- SHOW FLASH CONTENT -->
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
                                <i class="icon-users font-dark"></i>
                                <span class="caption-subject bold uppercase"> New Product Posts</span>
                            </h4>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table id="new-products-table" class="table table-striped table-bordered table-hover dt-responsive">
                            <thead>
                            <tr>
                                <th class="all">Name</th>
                                <th class="all">Thumbnail</th>
                                <th class="all">Category</th>
                                <th class="all">Subcategory</th>
                                <th class="min-tablet">Lender</th>
                                <th class="min-tablet">Address</th>
                                <th class="all">Actions</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>



@endsection