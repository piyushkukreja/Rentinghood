@extends('layouts.vendor_dashboard')

@section('scripts')

    @parent
    <script src="{{ asset('admin/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/bootstrap-modal.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/sweetalert2.all.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/moment.js')}}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/fullcalendar.js')}}" type="text/javascript"></script>
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('admin/js/vendor-calendar.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var base_url = '{{ \Illuminate\Support\Facades\URL::to('/') }}';
            AppCalendar.init(base_url, '{{ csrf_token() }}');
        });
    </script>
    <!-- END PAGE LEVEL SCRIPTS -->
@endsection

@section('head')
    @parent
    <link rel="stylesheet" href="{{asset('admin/css/fullcalendar.css')}}">
@endsection

@section('content')

    <div class="page-content">
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet light portlet-fit bordered calendar">
                    <div class="portlet-title">
                        <div class="caption font-blue-dark">
                            <h4 style="margin: 0;">
                                <i class="icon-calendar font-blue-dark"></i>
                                <span class="caption-subject bold uppercase"> CALENDAR </span>
                            </h4>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="events-calendar" class="has-toolbar"> </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
        <a id="edit-event-modal-trigger" class="hidden" data-toggle="modal" href="#edit-event-modal"></a>
        <div id="edit-event-modal" class="modal fade" tabindex="-1" data-width="760">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Edit Event</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h4></h4>
                        <input name="event_title" class="form-control" type="text">
                    </div>
                    <div class="col-md-12">
                        <h4>Date</h4>
                        <input name="event_date" class="form-control no-arrows" type="date">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="edit-event-modal-close" type="button" data-dismiss="modal" class="btn btn-outline dark">Cancel</button>
                <button id="event-edit" type="button" class="btn blue">Update</button>
                <button id="event-delete" type="button" class="btn red">Delete</button>
            </div>
        </div>
    </div>

@endsection