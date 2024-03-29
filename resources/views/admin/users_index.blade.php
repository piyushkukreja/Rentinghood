@extends('layouts.admin_dashboard')

@section('scripts')

    @parent
    <script src="{{ asset('admin/js/sweetalert2.all.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/admin-users.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        @if(\Illuminate\Support\Facades\App::environment('local'))
            var base_url = '{{ route('home') }}/a';
        @else
            var base_url = '{{ route('admin.index') }}';
        @endif
        jQuery(document).ready(function() {
            TableDatatablesResponsive.init(base_url);
        });

        function deleteUser(id) {
            swal({
                title: 'Are you sure you want to delete this user?',
                type: 'warning',
                showCancelButton: true,
                buttonsStyling: false,
                allowEnterKey: false,
                confirmButtonClass: 'btn btn-warning',
                cancelButtonClass: 'btn btn-info',
            }).then((result) => {
                if (result.value) {
                var csrf = '{{ csrf_token() }}';
                var deleteForm = $('<form action="' + base_url + '/users/' + id + '" method="POST">' +
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
                                <span class="caption-subject bold uppercase"> Users Table</span>
                            </h4>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <table id="users-table" class="table table-striped table-bordered table-hover dt-responsive">
                            <thead>
                            <tr>
                                <th class="all">Name</th>
                                <th class="min-tablet">Email</th>
                                <th class="min-tablet">Contact</th>
                                <th class="min-tablet">Address</th>
                                <th class="all">Lat</th>
                                <th class="all">Lng</th>
                                <th class="all">Verified</th>
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