@extends('layouts.vendor_dashboard')
@section('head')
    @parent
    <link rel="stylesheet" href="{{ asset('admin/css/dropzone/basic.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/dropzone/dropzone.min.css') }}">
@endsection
@section('scripts')
    @parent
    <script src="{{ asset('admin/js/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/js/dropzone/form-dropzone.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
    </script>
@endsection
@section('content')
    <div class="page-content" style="min-height: 555px;">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>Bulk Upload Products</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN SAMPLE FORM PORTLET-->
                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form id="bulk-upload-form" role="form" action="{{ route('vendor.products.upload') }}" method="post" enctype="multipart/form-data">
                            <div class="form-body">
                                <div class="form-group">
                                    <label>Choose Excel File</label>
                                    <div class="input-group">
                                        <input type="file" class="form-control" id="import-file" name="import-file"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                            <option class='form-control hidden' name='lender_id' id='lender_id' value={{Auth::id()}}>{{Auth::id()}}</option>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Product Pictures</label>
                                    <div class="dropzone dropzone-file-area" id="my-dropzone" style="width: 100%;">
                                        <h3 class="sbold">Drop files here or click to upload</h3>
                                        <p>Upload product pictures here</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" id="form-submit" class="btn blue">Import Excel</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END SAMPLE FORM PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection