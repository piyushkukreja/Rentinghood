@extends('layouts.admin_dashboard')
@section('head')
	@parent
	<style type="text/css">
		.swal2-container {
			z-index: 11000 !important;
		}
	</style>
@endsection
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

	</script>
@endsection
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
								<span class="caption-subject bold uppercase">Categories Table</span>
							</h4>
						</div>
					</div>
					<div class="portlet-body">
						<div class="table-toolbar">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-group">
										<input type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-category-modal" id="add-category-modal-trigger" name="add-category-btn" value="Add Category" style=" margin-bottom: 20px ">
                                    </div>
                                </div>
                            </div>
                        </div>
						<table id="categories-table" class="table table-striped table-bordered table-hover dt-responsive">
							<thead>
							<tr>
								<th class="all">Name</th>
								<th class="all">Edit</th>
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
				<form id="edit-form" action="{{ route('categories.store') }}" method="post">
					{{ csrf_field() }}
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Edit Category</h4>
					</div>
					<div class="modal-body">
						<div>
							<label> Enter Category: </label>
							<input type="text" class="form-control" name="name">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" id="update-category">Update</button>
						<button type="button" class="btn btn-default" data-dismiss="modal" name="modal-close">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
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
							<input type="text" class="form-control" name="name">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary" id="add-category">Add Category</button>
						<button type="button" class="btn btn-default" data-dismiss="modal" name="modal-close">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection
