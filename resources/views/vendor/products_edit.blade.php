@extends('layouts.vendor_dashboard')

@section('scripts')
    @parent
    <script src="{{ asset('admin/js/sweetalert2.all.js') }}" type="text/javascript"></script>
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDtfAuKKrycjdbscKGGfbCg0R5udw3N73g&amp;libraries=places"></script>
    <script src="{{ asset('js/jquery.geocomplete.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var productForm = $('#product-form');
            var csrf_token = '{{ csrf_token() }}';

            function ucwords (str) {
                str = str.replace('_', ' ');
                return (str + '')
                    .replace(/^(.)|\s+(.)/g, function ($1) {
                        return $1.toUpperCase()
                    });
            }

            //js for SUBCATERGORIES
            var categorySelector = productForm.find($('#category_id'));

            function changeSubcategories() {

                var subcategorySelector = productForm.find($('#subcategory_id'));
                subcategorySelector.empty();
                var category_id = categorySelector.val();

                subcategorySelector.empty();
                subcategorySelector.append('<option value="" selected disabled>Select a subcategory</option>');

                if (category_id != '') {
                    $.ajax(
                        {
                            type: 'POST',
                            url: '{{ route('get_subcategories') }}',
                            data: {_token: csrf_token, category_id: category_id},
                            dataType: 'JSON',
                            success: function (returned_data) {

                                $.each(returned_data, function (i, d) {
                                    subcategorySelector.append('<option value="' + d.id + '">' + ucwords(d.name) + '</option>');
                                });

                            }
                        }
                    );
                }
            }

            categorySelector.on('change', function () {
                changeSubcategories();
            });

            //js for ADDRESS
            var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(19.296441, 72.9864994),
                new google.maps.LatLng(18.8465126, 72.9042434)
            );

            $("#address").geocomplete({
                location: '{{ Auth::user()->address }}',
                details: "#product_latlng",
                bounds: defaultBounds
            }).bind("geocode:result", function (event, result) {
                console.log('Product :' + result);
            });

            //js for DURATION AND RATES
            function changeRequiredStates(form_container) {
                var rate1 = form_container.find('input[name="rate_1"]');
                var rate2 = form_container.find('input[name="rate_2"]');
                var rate3 = form_container.find('input[name="rate_3"]');
                switch (form_container.find('select[name="duration"]').val()) {
                    case '0' :
                        rate1.prop('required', true).parent().slideDown();
                        rate2.prop('required', false).parent().slideUp();
                        rate3.prop('required', false).parent().slideUp();
                        break;
                    case '1' :
                        rate1.prop('required', true).parent().slideDown();
                        rate2.prop('required', true).parent().slideDown();
                        rate3.prop('required', false).parent().slideUp();
                        break;
                    case '2' :
                        rate1.prop('required', true).parent().slideDown();
                        rate2.prop('required', true).parent().slideDown();
                        rate3.prop('required', true).parent().slideDown();
                        break;
                }
            }

            changeRequiredStates(productForm);
            $(productForm).find('select[name="duration"]').on('change', function () {
                changeRequiredStates(productForm);
            });

            //js for IMAGES
            $('input[type="radio"][name="product_thumbnail"]').change(function() {
                var radioInput = this;
                $.ajax({
                    url: '{{ route('vendor.products.update-image', [$data['product']->id]) }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: { _token: '{{ csrf_token() }}', file_id: radioInput.value}
                });
            });

            $('a.remove').on('click', function () {
                var link = $(this);
                $.ajax({
                    url: '{{ route('vendor.products.remove-image', [$data['product']->id]) }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: { _token: '{{ csrf_token() }}', picture_id: parseInt(link.parents('tr').find('td.product-picture-id').html()) },
                    success: function (response) {
                        if(response.status === 'failed') {
                            swal(
                                "Error",
                                response.message, // had a missing comma
                                "error"
                            )
                        } else {
                            link.parents('tr').hide();
                        }
                    }
                });
            })
        })
    </script>
@endsection
@section('content')
    <div class="page-content">
        <!-- BEGIN PAGE HEAD-->
        <div class="page-head">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1>{{ $data['product']->name }} @if($data['product']->verified == 0) <span class="small">review pending</span>@endif</h1>
            </div>
            <!-- END PAGE TITLE -->
        </div>
        <!-- END PAGE HEAD-->
        <!-- SHOW FLASH CONTENT -->
        @if(Session::has('success'))
            <p class="alert alert-success">{{ session('success') }}</p>
    @endif
    <!-- END FLASH CONTENT -->
        <!-- BEGIN PAGE BASE CONTENT -->
        <div class="row">
            <div class="col-md-12">
                <div class="portlet" style="margin-top: -20px;">
                    <div class="portlet-body">
                        <div class="tabbable-bordered">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#tab_general" data-toggle="tab"> General </a>
                                </li>
                                <li>
                                    <a href="#tab_images" data-toggle="tab"> Images </a>
                                </li>
                                <li>
                                    <a href="#tab_history" data-toggle="tab"> History </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_general">
                                    {!! Form::model($data['product'], ['id' => 'product-form', 'method' => 'PUT', 'action' => ['AdminController@productsUpdate', $data['product']->id], 'class' => 'form-horizontal', 'role' => 'form']) !!}
                                    <div class="form-body">
                                        <div class="form-group">
                                            {!! Form::label('name', 'Name', array('class' => 'col-md-2 control-label')) !!}
                                            <div class="col-md-10">
                                                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('description', 'Description', array('class' => 'col-md-2 control-label')) !!}
                                            <div class="col-md-10">
                                                {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('category_id', 'Category', array('class' => 'col-md-2 control-label')) !!}
                                            <div class="col-md-10">
                                                {!! Form::select('category_id', $data['categories'], $data['product']->category_id, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('subcategory_id', 'Subcategory', array('class' => 'col-md-2 control-label')) !!}
                                            <div class="col-md-10">
                                                {!! Form::select('subcategory_id', $data['subcategories'], $data['product']->subcategory_id, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('duration', 'Duration', array('class' => 'col-md-2 control-label')) !!}
                                            <div class="col-md-10">
                                                {!! Form::select('duration', [0 => 'Short Term', 1 => 'Weekly', 2 => 'Long Term'], $data['product']->duration, ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-offset-2 col-md-10">
                                                <div class="col-md-4" style="padding: 0;">
                                                    {!! Form::number('rate_1', null, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-md-4" style="padding: 0;">
                                                    {!! Form::number('rate_2', null, ['class' => 'form-control']) !!}
                                                </div>
                                                <div class="col-md-4" style="padding: 0;">
                                                    {!! Form::number('rate_3', null, ['class' => 'form-control']) !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('address', 'Address', array('class' => 'col-md-2 control-label')) !!}
                                            <div class="col-md-10">
                                                {!! Form::text('address', null, ['class' => 'form-control ']) !!}
                                            </div>
                                            <div id="product_latlng" class="hidden">
                                                <input name="lat" type="hidden" value="">
                                                <input name="lng" type="hidden" value="">
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-2 col-md-4">
                                                    {!! Form::submit('Update', ['class' => 'btn green  col-offset-4']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                                {{--<div class="tab-pane" id="tab_meta">
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Meta Title:</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control maxlength-handler" name="product[meta_title]" maxlength="100" placeholder="">
                                                <span class="help-block"> max 100 chars </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Meta Keywords:</label>
                                            <div class="col-md-10">
                                                <textarea class="form-control maxlength-handler" rows="8" name="product[meta_keywords]" maxlength="1000"></textarea>
                                                <span class="help-block"> max 1000 chars </span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Meta Description:</label>
                                            <div class="col-md-10">
                                                <textarea class="form-control maxlength-handler" rows="8" name="product[meta_description]" maxlength="255"></textarea>
                                                <span class="help-block"> max 255 chars </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                --}}
                                <div class="tab-pane" id="tab_images">
                                    <div class="text-align-reverse margin-bottom-10">
                                        <a href="javascript:;" class="btn btn-primary">
                                            <i class="fa fa-share"></i> Upload Files </a>
                                    </div>
                                    <div class="row">
                                        <div id="tab_images_uploader_filelist" class="col-md-6 col-sm-12"> </div>
                                    </div>
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                        <tr role="row" class="heading">
                                            <th width="10%"> ID </th>
                                            <th width="30%"> Image </th>
                                            <th width="25%"> Thumbnail </th>
                                            <th width="25%"> Actions </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data['product_pictures'] as $picture)
                                            <tr>
                                                <td class="product-picture-id">
                                                    {{ $picture->id }}
                                                </td>
                                                <td>
                                                    <img class="img-responsive" src="{{ asset('img/uploads/products/large') . '/' . $picture->file_name }}">
                                                </td>
                                                <td>
                                                    <div class="md-radio">
                                                        <input type="radio" id="radio{{ $picture->id }}" name="product_thumbnail" class="md-radiobtn"
                                                               value="{{ $picture->id }}" {{ $data['product']->image == $picture->file_name ? 'checked' : '' }}>
                                                        <label for="radio{{ $picture->id }}">
                                                            <span class="inc"></span>
                                                            <span class="check"></span>
                                                            <span class="box"></span></label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" class="remove btn btn-default btn-sm">
                                                        <i class="fa fa-times"></i> Remove </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tab_reviews">
                                    <div class="table-container">
                                        <table class="table table-striped table-bordered table-hover" id="datatable_reviews">
                                            <thead>
                                            <tr role="row" class="heading">
                                                <th width="5%"> Review&nbsp;# </th>
                                                <th width="10%"> Review&nbsp;Date </th>
                                                <th width="10%"> Customer </th>
                                                <th width="20%"> Review&nbsp;Content </th>
                                                <th width="10%"> Status </th>
                                                <th width="10%"> Actions </th>
                                            </tr>
                                            <tr role="row" class="filter">
                                                <td>
                                                    <input type="text" class="form-control form-filter input-sm" name="product_review_no"> </td>
                                                <td>
                                                    <div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">
                                                        <input type="text" class="form-control form-filter input-sm" readonly name="product_review_date_from" placeholder="From">
                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-sm default" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                    </div>
                                                    <div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
                                                        <input type="text" class="form-control form-filter input-sm" readonly name="product_review_date_to" placeholder="To">
                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-sm default" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-filter input-sm" name="product_review_customer"> </td>
                                                <td>
                                                    <input type="text" class="form-control form-filter input-sm" name="product_review_content"> </td>
                                                <td>
                                                    <select name="product_review_status" class="form-control form-filter input-sm">
                                                        <option value="">Select...</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="approved">Approved</option>
                                                        <option value="rejected">Rejected</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="margin-bottom-5">
                                                        <button class="btn btn-sm btn-success filter-submit margin-bottom">
                                                            <i class="fa fa-search"></i> Search</button>
                                                    </div>
                                                    <button class="btn btn-sm btn-danger filter-cancel">
                                                        <i class="fa fa-times"></i> Reset</button>
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody> </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_history">
                                    <div class="table-container">
                                        <table class="table table-striped table-bordered table-hover" id="datatable_history">
                                            <thead>
                                            <tr role="row" class="heading">
                                                <th width="25%"> Datetime </th>
                                                <th width="55%"> Description </th>
                                                <th width="10%"> Notification </th>
                                                <th width="10%"> Actions </th>
                                            </tr>
                                            <tr role="row" class="filter">
                                                <td>
                                                    <div class="input-group date datetime-picker margin-bottom-5" data-date-format="dd/mm/yyyy hh:ii">
                                                        <input type="text" class="form-control form-filter input-sm" readonly name="product_history_date_from" placeholder="From">
                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-sm default date-set" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                    </div>
                                                    <div class="input-group date datetime-picker" data-date-format="dd/mm/yyyy hh:ii">
                                                        <input type="text" class="form-control form-filter input-sm" readonly name="product_history_date_to" placeholder="To">
                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-sm default date-set" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control form-filter input-sm" name="product_history_desc" placeholder="To" /> </td>
                                                <td>
                                                    <select name="product_history_notification" class="form-control form-filter input-sm">
                                                        <option value="">Select...</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="notified">Notified</option>
                                                        <option value="failed">Failed</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <div class="margin-bottom-5">
                                                        <button class="btn btn-sm btn-default filter-submit margin-bottom">
                                                            <i class="fa fa-search"></i> Search</button>
                                                    </div>
                                                    <button class="btn btn-sm btn-danger-outline filter-cancel">
                                                        <i class="fa fa-times"></i> Reset</button>
                                                </td>
                                            </tr>
                                            </thead>
                                            <tbody> </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>
@endsection