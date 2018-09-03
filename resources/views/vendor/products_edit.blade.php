@extends('layouts.vendor_dashboard')

@section('scripts')
    @parent
    <script src="{{ asset('admin/js/sweetalert2.all.js') }}" type="text/javascript"></script>
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDtfAuKKrycjdbscKGGfbCg0R5udw3N73g&amp;libraries=places"></script>
    <script src="{{ asset('js/jquery.geocomplete.min.js') }}"></script>
    <script src="{{ asset('admin/js/admin-edit-product.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            @if(\Illuminate\Support\Facades\App::environment('local'))
                var base_url = '{{ route('home') }}/vendor';
            @else
                var base_url = '{{ route('vendor.index') }}';
            @endif
            var product = '@php echo json_encode($data['product']); @endphp';
            jQuery(document).ready(function() {
                ProductEditor.init(base_url, '{{ csrf_token() }}', JSON.parse(product), 'vendor');
            });
        })
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
                            <h4 style="margin: 0; display: inline-block; margin-right: 10px;">
                                <i class="icon-user font-dark"></i>
                                <span class="caption-subject bold uppercase"> {{ $data['product']->name }} </span>
                            </h4>
                            <label style="margin: 0;" class="mt-checkbox"> Accepted
                                <input id="accepted-checkbox" type="checkbox" name="verified">
                                <span></span>
                            </label>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabbable-line">
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