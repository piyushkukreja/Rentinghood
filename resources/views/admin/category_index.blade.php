@extends('layouts.admin_dashboard')

@section('scripts')

	@parent
	<script src="{{ asset('admin/js/sweetalert2.all.js') }}" type="text/javascript"></script>
	<script src="{{ asset('admin/js/datatable.js') }}" type="text/javascript"></script>
	<script src="{{ asset('admin/js/datatables.min.js') }}" type="text/javascript"></script>
	<script src="{{ asset('admin/js/datatables.bootstrap.js') }}" type="text/javascript"></script>
	<script src="{{ asset('admin/js/categories-datatables-responsive.js') }}" type="text/javascript"></script>
	<script type="text/javascript">
        var base_url = '{{ \Illuminate\Support\Facades\URL::to('/') }}';
        jQuery(document).ready(function() {
            TableDatatablesResponsive.init(base_url);
        });
        function openEditModal(id, name) {
            $('#edit-category-modal-trigger').trigger('click');//triggers modal on click with the specified id
			//below line gets the name from the same page instead of fetching from db
            $('#edit-category-modal').find('input[name="category-name"]').val(name);//this finds the name with the associated id and sets the value of input in modal
            $('#edit-form').attr('action', base_url + '/a/categories/' + id);//to use post method for updating data in db and page
        }

        function openAddModal() {
            $('#add-category-modal-trigger').trigger('click');//triggers modal on click with the specified id
            $('#add-form').attr('action', base_url + '/a/categories/');//to use post method for updating data in db and page
        }


	</script>
@endsection
@section('content')
	<!--<div class="page-content">
	<div class="col-xs-6">
		<h3>Add Category</h3>
		<form action="" method="POST"><!--form will use post method to communicate with server
			<div class="form-group">
				<label for="cat_title">Category Title</label>
				<input class="form-control" type="text" name="cat_title">
			</div>
			<div class="form-group">
				<input class="btn btn-primary" type="submit" name="submit" value="Add Category">
			</div>
		</form>
	</div>
	</div>-->


@section('content')

	<div class="page-content">
		<!-- BEGIN PAGE HEAD-->
		<div class="page-head">
			<!-- BEGIN PAGE TITLE -->
			<div class="page-title">
				<h1>Categories</h1>
			</div>
			<!-- END PAGE TITLE -->
		</div>
		<!-- END PAGE HEAD-->
		<!-- BEGIN PAGE BREADCRUMB -->
		<ul class="page-breadcrumb breadcrumb">
			<li>
				<a href="{{ route('categories.index') }}">Categories</a>
			</li>
		</ul>
		<!-- END PAGE BREADCRUMB -->
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
								<i class="icon-list font-dark"></i>
								<span class="caption-subject bold uppercase"> Categories Table</span>
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
						<input type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-category-modal" id="add-category-modal-trigger" name="add-category-btn" value="Add Category" style=" margin-bottom: 20px ">
						<table id="categories-table" class="table table-striped table-bordered table-hover dt-responsive">
							<thead>
							<tr>
								{{--<th class="all">
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="group-checkable" data-set="#users-table .checkboxes" />
                                        <span></span>
                                    </label>
                                </th>--}}
								<th class="all">Name</th>
								<th class="all">Update</th>
								{{--<th class="min-tablet">Email</th>
								<th class="min-tablet">Contact</th>
								<th class="min-tablet">Address</th>
								<th class="all">Lat</th>
								<th class="all">Lng</th>
								<th class="all">Verified</th>
								--}}
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

	<div id="edit-category-modal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<form id="edit-form" action="" method="post">
					{{ csrf_field() }}
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Edit Category</h4>
				</div>
				<div class="modal-body">
					<div>
						<label> Enter Category: </label>
						<input type="text" class="form-control" name="category-name">
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="edit-modal-update">Update</button>
					<button type="button" class="btn btn-default" data-dismiss="modal" name="modal-close">Close</button>
				</div>
				</form>
			</div>

		</div>
	</div>
	<a href="#"></a>
	<input type="button" class="btn btn-primary hidden" data-toggle="modal" data-target="#edit-category-modal" id="edit-category-modal-trigger" name="edit-category-btn" value="Edit Category">



	<div id="add-category-modal" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">

			<!-- Modal content-->
			<div class="modal-content">
				<form id="add-form" action="" method="post">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add Category</h4>
					</div>
					<div class="modal-body">
						<div>
							<label> Enter Category: </label>
							<input type="text" class="form-control" name="category-name">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" id="add-modal-update">Add Category</button>
						<button type="button" class="btn btn-default" data-dismiss="modal" name="modal-close">Close</button>
					</div>
				</form>
			</div>

		</div>
	</div>
	<a href="#"></a>

@endsection
