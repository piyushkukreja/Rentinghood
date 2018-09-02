@extends('layouts.admin_dashboard')

@section('head')

    @parent
    <style type="text/css">
        i.fa.fa-remove {
            width: 14px;
        }
        .btn-action {
            padding: 3px 6px 3px 6px;
            margin-right: 8px;
        }
    </style>

@endsection

@section('scripts')

    @parent
    <script src="{{ asset('admin/js/sweetalert2.all.js') }}" type="text/javascript"></script>
    <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDtfAuKKrycjdbscKGGfbCg0R5udw3N73g&amp;libraries=places"></script>
    <script src="{{ asset('js/jquery.geocomplete.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            //Initialisations
            var id = {{ $data['user']->id }};
            @if(\Illuminate\Support\Facades\App::environment('local'))
                var base_url = '{{ route('home') }}/a';
            @else
                var base_url = '{{ route('admin.index') }}';
            @endif
            var noteTemplate = $('#timeline-item-format').html();
            $('#timeline-item-format').remove();
            var productTemplate = $('#product-template').html();
            $('#product-template').remove();

            //JS for Address
            var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(19.296441, 72.9864994),
                new google.maps.LatLng(18.8465126, 72.9042434)
            );

            $("#address").geocomplete({
                location: '{{ $data['user']->address }}',
                details: "#user_latlng",
                bounds: defaultBounds
            }).bind("geocode:result", function (event, result) {
                console.log(result);
            });

            //JS for Notes
            function getNotes() {
                var notesContainer = $('#notes-container');
                notesContainer.html('');
                $.ajax({
                    url: base_url + '/users/' + id + '/notes',
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {
                        if(response.status === 'success') {
                            $.each(response.data, function (i, note) {
                                var noteDiv = $(noteTemplate);
                                noteDiv.find('a.timeline-body-title').html(note.admin.first_name + ' ' + note.admin.last_name);
                                noteDiv.find('.timeline-body-time').html(note.created_at);
                                noteDiv.find('img').attr('src', '{{ asset('img') }}' + '/' + note.admin.profile_picture);
                                noteDiv.find('.timeline-body-content span').html(note.note);
                                notesContainer.append(noteDiv);
                            })
                        }
                    }
                });
            }

            $('#add-note-btn').on('click', function () {
                swal({
                    title: 'Add Note',
                    input: 'textarea',
                    showCancelButton: true,
                }).then(function (noteInput) {
                    $.ajax({
                        type: "POST",
                        url: base_url + '/users/' + id + '/notes',
                        data: { _token: '{{ csrf_token() }}', note: noteInput },
                        cache: false,
                        success: function(response) {
                            swal(
                                "Success!",
                                "Your note has been saved!",
                                "success"
                            );
                            $('a[data-toggle="tab"][href="#tab_15_2"]').trigger('click');
                        },
                        failure: function (response) {
                            swal(
                                "Internal Error",
                                "Oops, your note was not saved.", // had a missing comma
                                "error"
                            )
                        }
                    });
                },
                function (dismiss) {
                    if (dismiss === "cancel") {
                        swal(
                            "Cancelled",
                            "Canceled Note",
                            "error"
                        )
                    }
                });
            });

            //JS for Inventory
            function loadInventory() {
                var productsContainer = $('.mt-element-card.mt-element-overlay').children('.row');
                productsContainer.html('');
                $.ajax({
                    url: base_url + '/users/' + id + '/inventory',
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {
                        if(response.status === 'success') {
                            if(response.data.length === 0) {
                                swal(
                                    "Empty Inventory",
                                    "",
                                    "error"
                                )
                            }
                            $.each(response.data, function (i, product) {
                                var productDiv = $(productTemplate);
                                productDiv.find('.mt-card-item').css('overflow', 'hidden');
                                productDiv.find('img').attr('src', '/img/uploads/products/small/' + product.image);
                                productDiv.find('h3.mt-card-name').html('<span style="white-space: nowrap;">' + product.name + '</span>');
                                productDiv.find('p.mt-card-desc').html(product.subcategory_id);
                                productDiv.find('a.edit').attr('href', base_url + '/products/' + product.id + '/edit');
                                productDiv.find('.md-checkbox').find('input').attr('id', 'checkbox' + product.id).attr('checked', product.availability === 1).on('change',  function () {
                                    if($(this).is(":checked"))
                                        updateAvailability(product.id, 1);
                                    else
                                        updateAvailability(product.id, 0);
                                });
                                productDiv.find('.md-checkbox').find('label').attr('for', 'checkbox' + product.id);
                                productsContainer.append(productDiv);
                            });
                        }
                    }
                });
            }

            function updateAvailability(id, availability) {
                var csrf = '{{ csrf_token() }}';
                $.ajax({
                    type: 'POST',
                    url: '{{ route('update_availability') }}',
                    dataType: 'JSON',
                    data: {_token: csrf, product_id: id, availability: availability},
                });
            }

            $('a[data-toggle="tab"]').on('click', function () {
                switch($(this).attr('href')) {
                    case '#tab_15_1' :
                        //tables[0].api().ajax.reload();
                        break;
                    case '#tab_15_2' :
                        getNotes();
                        break;
                    case '#tab_15_3' :
                        loadInventory();
                        break;
                }
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
                            <h4 style="margin: 0;">
                                <i class="icon-user font-dark"></i>
                                <span class="caption-subject bold uppercase"> User - {{ $data['user']->first_name . ' ' . $data['user']->last_name }}</span>
                            </h4>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a href="#tab_15_1" data-toggle="tab"> Profile </a>
                                </li>
                                <li>
                                    <a href="#tab_15_2" data-toggle="tab"> Notes </a>
                                </li>
                                <li>
                                    <a href="#tab_15_3" data-toggle="tab"> Inventory </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_15_1">
                                    {!! Form::model($data['user'], ['method' => 'PUT', 'action' => ['AdminController@usersUpdate', $data['user']->id], 'class' => 'form-horizontal', 'role' => 'form']) !!}
                                    <div class="form-body">
                                        <div class="form-group">
                                            {!! Form::label('first_name', 'First Name', array('class' => 'col-md-4  control-label'), false) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('first_name', null, ['class' => 'form-control ']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('last_name', 'Last Name', array('class' => 'col-md-4  control-label'), false) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('last_name', null, ['class' => 'form-control ']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('email', 'E-mail', array('class' => 'col-md-4  control-label'), false) !!}
                                            <div class="col-md-4">
                                                {!! Form::email('email', null, ['class' => 'form-control ']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('contact', 'Contact', array('class' => 'col-md-4  control-label'), false) !!}
                                            <div class="col-md-4">
                                                {!! Form::number('contact', null, ['class' => 'no-arrows form-control ']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('address', 'Address', array('class' => 'col-md-4  control-label')) !!}
                                            <div class="col-md-4">
                                                {!! Form::text('address', null, ['class' => 'form-control ']) !!}
                                            </div>
                                            <div id="user_latlng" class="hidden">
                                                <input name="lat" type="hidden" value="">
                                                <input name="lng" type="hidden" value="">
                                            </div>
                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-4 col-md-4">
                                                    {!! Form::submit('Update', ['class' => 'btn green  col-offset-4']) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                                <div class="tab-pane" id="tab_15_2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light portlet-fit bg-inverse ">
                                                <div class="portlet-title">
                                                    <div class="table-toolbar" style="margin-bottom: 0;">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="btn-group">
                                                                    <a id="add-note-btn" class="btn sbold green" href="javascript:;"> Add
                                                                        <i class="fa fa-plus"></i>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div id="notes-container" class="timeline  white-bg ">
                                                        <!-- TIMELINE ITEM -->
                                                        <div id="timeline-item-format" class="hidden">
                                                            <div class="timeline-item">
                                                                <div class="timeline-badge">
                                                                    <img class="timeline-badge-userpic"
                                                                         src="">
                                                                </div>
                                                                <div class="timeline-body">
                                                                    <div class="timeline-body-arrow"></div>
                                                                    <div class="timeline-body-head">
                                                                        <div class="timeline-body-head-caption">
                                                                            <a href="javascript:;" class="timeline-body-title font-blue-madison"></a>
                                                                            <span class="timeline-body-time font-grey-cascade"></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="timeline-body-content">
                                                                        <span class="font-grey-cascade"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- END TIMELINE ITEM -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tab_15_3">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="portlet light portlet-fit bordered">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <i class=" icon-layers font-green"></i>
                                                        <span class="caption-subject font-green bold uppercase">Products</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="mt-element-card mt-element-overlay">
                                                        <div class="row">
                                                            <div id="product-template">
                                                                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                                    <div class="mt-card-item">
                                                                        <div class="mt-card-avatar mt-overlay-1">
                                                                            <img src="" />
                                                                            <div class="mt-overlay">
                                                                                <ul class="mt-info">
                                                                                    <li>
                                                                                        <a class="edit btn default btn-outline" href="">
                                                                                            <i class="fa fa-edit"></i>
                                                                                        </a>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mt-card-content">
                                                                            <h3 class="mt-card-name"></h3>
                                                                            <p class="mt-card-desc font-grey-mint"></p>
                                                                            <div class="md-checkbox-list" style="margin-left: 20px;">
                                                                                <div class="md-checkbox" style="width: 100px;">
                                                                                    <input type="checkbox" class="md-check">
                                                                                    <label>
                                                                                        <span></span>
                                                                                        <span class="check"></span>
                                                                                        <span class="box"></span> Availability </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- END EXAMPLE TABLE PORTLET-->
            </div>
        </div>
        <!-- END PAGE BASE CONTENT -->
    </div>

@endsection