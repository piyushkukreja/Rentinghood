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
                                <i class="icon-book-open font-dark"></i>
                                <span class="caption-subject bold uppercase"> New Orders</span>
                            </h4>
                        </div>
                    </div>
                    <div class="portlet-body">
                        {{--<div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group">
                                        <a class="btn sbold green" href="{{ route('users.create') }}"> {{ __('words.add') }}
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>--}}
                        <table id="new-orders-table" class="table table-striped table-bordered table-hover dt-responsive">
                            <thead>
                            <tr>
                                {{--<th class="all">
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="group-checkable" data-set="#users-table .checkboxes" />
                                        <span></span>
                                    </label>
                                </th>--}}
                                {{--<th class="min-tablet">
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="checkboxes group-checkable" data-set="#new-orders-table .checkboxes" value="">
                                        <span></span>
                                    </label>
                                </th>--}}
                                <th class="all">Renter Name</th>
                                <th class="min-tablet" width="50">Renter Address</th>
                                <th class="all">Product Name</th>
                                <th class="all">Product Image</th>
                                <th class="all">From_Date</th>
                                <th class="all">To_Date</th>
                                <th class="all">Actions</th>
                                <!--<th class="all">delete</th>-->
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