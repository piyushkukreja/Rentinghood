@extends('layouts.vendor_dashboard')

@section('scripts')

    @parent
    <script src="{{ asset('admin/js/sweetalert2.all.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/new-orders-datatables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/full_calendar.js') }}" type="text/javascript"></script>
    {{--<script src="{{ asset('admin/js/fcalendar.js') }}" type="text"></script>--}}
    <script type="text/javascript">
        var base_url = '{{ \Illuminate\Support\Facades\URL::to('/') }}';
        jQuery(document).ready(function() {
            var csrf = '{{ csrf_token() }}';
            TableDatatablesResponsive.init(base_url, csrf);
        });


    </script>
@endsection

@section('content')

    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Calendar</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BREADCRUMB -->
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="{{ route('vendor.calendar') }}">Events</a>
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
                <div class="calendar">
                    <div id="appointments-create">
                        <div id="create-appointments-modal"></div>
                    </div>
                </div>
                <div id="event_date_range"></div>
                <div class="start_hidden"></div>
                <div class="end_hidden"></div>
                <div id="remote_container"></div>
                <div id="new_event"></div>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>



@endsection