@extends('layouts.app')
@extends('layouts.navbar')
@section('content')
    <style>
        #alert .fa-close {
            position: absolute;
            right: 0;
            top: 20px;
            margin-right: 15px;
            cursor: pointer;
        }
        .menu-vertical li:not(:hover):not(.dropdown--active) {
            opacity: 1;
        }
        .message i.fa-check-circle, .message i.fa-times-circle {
            font-size: 2em;
            float: right;
        }
        .message .fa-check-circle {
            color: #4ebf56;
        }
        .message .fa-times-circle {
            color: #e23636;
        }
        .loading {
            background: url({{ asset('img/loading.gif') }}) center no-repeat;
            color: transparent;
        }
        .loading * {
            color: transparent;
        }
        .account-tab {
            min-height: 500px;
        }
        @media (max-width: 767px) {
            .boxed div[class*='col-']:not(.boxed) {
                padding-left: 15px;
                padding-right: 15px;
            }
            .tabs li:not(:last-child) {
                border-bottom: 1px solid #ECECEC;
                border-right: 1px solid #ECECEC;
            }
            .tabs li {
                display: inline-block;
            }
        }
        #account-messages h4, #account-messages p {
            margin-bottom: 0.5em;
        }
        #account-messages .boxed {
            padding: 1.2em;
            margin-bottom: 0.7em;
            border-width: 1.5px;
        }
        .badge-count {
            line-height: 1.5;
            display: inline-block;
            text-align: center;
            background-color: #3b5998;
            width: 1.8em;
            height: 1.5em;
            color: white;
            border-radius: 50%;
            margin: auto;
        }
        .account-tab {
            min-height: 100vh;
        }
    </style>

    <div id="request_message_template" style="display: none;">
        <div class="message boxed boxed--border  bg--secondary boxed--lg box-shadow">
            <div>
                <h4 style="display: inline-block;" class="product_name"></h4>
            </div>
            <p class="new_request" style="clear: both;">
                <span class="first_name"></span> <span class="last_name"></span> has placed a request for <span class="product_name"></span> for
                <span class="h5"><span class="from_date"></span> - <span class="to_date"></span></span>
            </p>
            <p class="accepted_request" style="clear: both;">
                You accepted the request by <span class="first_name"></span> <span class="last_name"></span> for <span class="product_name"></span> for
                <span class="h5"><span class="from_date"></span> - <span class="to_date"></span></span>
            </p>
            <p class="rejected_request" style="clear: both;">
                You did not approve the request by <span class="first_name"></span> <span class="last_name"></span> for <span class="product_name"></span> for
                <span class="h5"><span class="from_date"></span> - <span class="to_date"></span></span>
            </p>
            <div class="response_buttons_div">
                <a class="btn btn--primary accept" href="#">
                    <span class="btn__text">
                        Accept
                    </span>
                </a>
                <a class="btn btn--primary reject" href="#">
                    <span class="btn__text">
                        Reject
                    </span>
                </a>
            </div>
            <div class="contact_div card text-center" style="display: none">Contact : <span class="h4" style="margin-bottom: 0.5em"><span class="first_name"></span> <span class="last_name"></span>, <span class="contact"></span></span></div>
        </div>
    </div>

    <div id="reply_message_template" style="display: none;">
        <div class="message boxed boxed--border bg--secondary boxed--lg box-shadow">
            <h4 class="product_name"></h4>
            <p class="request_pending">
                Your request for <span class="product_name"></span> for
                <span class="h5"><span class="from_date"></span> - <span class="to_date"></span></span>
                is pending and not yet been approved.
            </p>
            <p class="request_accepted">
                Your request for <span class="product_name"></span> for
                <span class="h5"><span class="from_date"></span> - <span class="to_date"></span></span>
                was accepted.
            </p>
            <p class="request_rejected">
                Your request for <span class="product_name"></span> for
                <span class="h5"><span class="from_date"></span> - <span class="to_date"></span></span>
                was not approved.
            </p>
            <div class="response_buttons_div">
                <a class="btn btn--primary okay" href="#">
                    <span class="btn__text">
                        Okay
                    </span>
                </a>
                <a class="btn btn--primary show_contact" href="#">
                    <span class="btn__text">
                        Get contact
                    </span>
                </a>
            </div>
            <div class="contact_div card text-center" style="display: none;">Contact : <span class="h4" style="margin-bottom: 0.5em"><span class="first_name"></span> <span class="last_name"></span>, <span class="contact"></span></span></div>
        </div>
    </div>
    <div id="messages_modal_template" class="hidden">
        <div class="modal-instance">
            <div id="new_message_modal" class="modal-container">
                <div class="modal-content">
                    <div class="boxed boxed--lg">
                        <h2>You have new messages</h2>
                        <hr class="short">
                        <p class="lead">
                            Visit the messages tabs to view requests for your products and to answer them.
                        </p>
                        <div class="text-center">
                            <a id="view_messages" class="btn btn--lg btn--primary type--uppercase" href="#">
                                <span class="btn__text">View messages</span>
                            </a>
                        </div>
                    </div>
                    <div class="modal-close modal-close-cross"></div>
                </div>
            </div>
            <a id="new_message_modal_trigger" href="#" class="modal-trigger hidden"></a>
        </div>
    </div>
    <div id="notifications_template" style="display: none;">
        <div class="notification pos-right pos-top col-11 col-sm-6 col-lg-4 notification--reveal"
             data-animation="from-top" data-notification-link="openNotification">
            <div class="boxed boxed--border border--round box-shadow">
                <div class="row">
                    <div class="col-12 col-lg-12 text-center" style="padding-bottom: 1em; height: 200px; margin-bottom: 1em;">
                        <img id="product_image" style="max-width: 100%;" alt="avatar" class="rounded-circle" src="">
                    </div>
                    <hr>
                    <div class="text-block col-12 col-lg-12">
                        <h5>Update your inventory</h5>
                        <p>Hey there, did you rent your <span id="product_name"></span> to <span
                                    id="renter_name"></span>?
                            Would you like to turn off its availability?</p>

                    </div>
                    <div class="col-md-6">
                        <button id="yes_close_notification" class="btn btn--primary btn--lg" style="width: 100%;">Yes
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button id="no_close_notification" class="btn btn--primary btn--lg" style="width: 100%;">NO
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="notification_trigger" data-notification-link="openNotification" style="display: none;"></div>
    <div class="main-container">
        <section class="bg--secondary space--sm">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12" style="display: none;">
                        @if (Session::has('success'))
                            <div id="alert" class="boxed boxed--sm boxed--border color--success">
                                <i class="fa fa-check-circle"></i> {{ Session::get('success') }}
                                <i class="fa fa-close"></i>
                            </div>
                        @elseif (Session::has('failure'))
                            <div id="alert" class="boxed boxed--sm boxed--border color--error">
                                <i class="fa fa-exclamation-circle"></i> {{ Session::get('failure') }}
                                <i class="fa fa-close"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <div class="boxed boxed--lg boxed--border">
                            <div class="text-block text-center">
                                <img style="border-radius: 50%; cursor: pointer;" alt="avatar"
                                     src="{{ asset('img/avatar.png') }}" class="image--sm"/>
                                <span class="h5">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                <span class="label">Verified</span>
                            </div>
                            <hr>
                            <div class="text-block">
                                <ul class="menu-vertical">
                                    <li>
                                        <a href="#" id="profile_link" class="tab-link"><i class="fa fa-user-circle"></i> Profile</a>
                                    </li>
                                    <li>
                                        <a href="#" id="password_link" class="tab-link"><i class="fa fa-key"></i> Password</a>
                                    </li>
                                    <li>
                                        <a href="#" id="lend_link" class="tab-link"><i style="padding-left: 1px; padding-right: 1px;" class="fa fa-plus-square"></i> Post a product</a>
                                    </li>
                                    <li>
                                        <a href="#" id="inventory_link" class="tab-link"><i class="fa fa-archive"></i> Inventory</a>
                                    </li>
                                    <li>
                                        <a href="#" id="messages_link" class="tab-link"><i class="fa fa-comment"></i> Messages</a>
                                        <span id="message_count" style="display: none;" class="badge-count">0</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="boxed boxed--lg boxed--border">
                            <div id="account-profile" style="@if($tab != 'profile')display: none;@endif" class="account-tab">
                                <h4>Profile</h4>
                                <form method="POST" action="{{ route('update_profile') }}">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>First Name:</label>
                                            <input id="first_name" type="text" name="first_name"
                                                   value="{{ Auth::user()->first_name }}" required>
                                            @if ($errors->has('first_name'))
                                                <span class="color--error"><strong>{{ $errors->first('first_name') }}</strong></span>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <label>Last Name:</label>
                                            <input id="last_name" type="text" name="last_name"
                                                   value="{{ Auth::user()->last_name }}" required>
                                            @if ($errors->has('last_name'))
                                                <span class="color--error"><strong>{{ $errors->first('last_name') }}</strong></span>
                                            @endif
                                        </div>

                                        <div class="col-md-12">
                                            <label>Email Address:</label>
                                            <input id="email" type="email" name="email"
                                                   value="{{ Auth::user()->email }}" required disabled>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Address:</label>
                                            <input id="user_address" name="address" type="text" value="">
                                            <div id="user_latlng" class="hidden">
                                                <input name="lat" type="hidden" value="">
                                                <input name="lng" type="hidden" value="">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-4" style="margin-top: 2em;">
                                            <input type="submit" class="btn btn--primary type--uppercase"
                                                   name="submit_profile" value="Save Profile">
                                        </div>

                                    </div>
                                </form>
                            </div>
                            <div id="account-password" style="@if($tab != 'password')display: none;@endif" class="account-tab">
                                <h4>Password</h4>
                                <p>Passwords must be at least 6 characters in length.</p>
                                <form method="POST" action="{{ route('update_profile') }}">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Old Password:</label>
                                            <input id="old_password" type="password" name="old_password" required/>
                                            @if ($errors->has('old_password'))
                                                <span class="color--error"><strong>{{ $errors->first('old_password') }}</strong></span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label>New Password:</label>
                                            <input id="password" type="password" name="password" required>
                                            @if ($errors->has('password'))
                                                <span class="color--error"><strong>{{ $errors->first('password') }}</strong></span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label>Retype New Password:</label>
                                            <input id="password-confirm" type="password" name="password_confirmation"
                                                   required>
                                        </div>
                                        <div class="col-lg-3 col-md-4">
                                            <input type="submit" class="btn btn--primary type--uppercase"
                                                   name="submit_password" value="Save Password">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="account-lend" style="@if($tab != 'lend')display: none;@endif" class="account-tab">
                                <h4>Post a product</h4>
                                <form id="lend-form" class="dropzone" method="POST"
                                      action="{{ route('lend_form_processing') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Category</label>
                                            <div class="input-select">
                                                <select id="category_id" name="category_id" required>
                                                    @if(!isset($category_id))
                                                        <option value="" selected disabled>Select a category</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? "selected" : "" }}>{{ ucwords(str_replace('_', ' ', $category->name)) }}</option>
                                                        @endforeach
                                                    @else
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" {{ $category_id == $category->id ? "selected" : "" }}>{{ ucwords(str_replace('_', ' ', $category->name)) }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>

                                            @if ($errors->has('category_id'))
                                                <span class="color--error"><strong>{{ $errors->first('category_id') }}</strong></span>
                                            @endif
                                        </div>

                                        <div class="col-md-12">
                                            <label>Sub-Category:</label>
                                            <div class="input-select">
                                                <select name="subcategory_id" id="subcategory_id"></select>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Product Name</label>
                                            <input id="name" type="text" name="name" value="{{ old('name') }}" required>

                                            @if ($errors->has('name'))
                                                <span class="color--error"><strong>{{ $errors->first('name') }}</strong></span>
                                            @endif
                                        </div>

                                        <div class="col-md-12">
                                            <label>Description</label>
                                            <textarea id="description" name="description"
                                                      required>{{ old('description') }}</textarea>

                                            @if ($errors->has('description'))
                                                <span class="color--error"><strong>{{ $errors->first('description') }}</strong></span>
                                            @endif
                                        </div>

                                        <div class="col-md-12">
                                            <label>Duration</label>
                                            <div class="input-select">
                                                <select id="duration" class="input-select" name="duration" required>
                                                    <option value="" selected disabled>Select a duration</option>
                                                    <option value="0" {{ old('duration') == 0 ? "selected" : "" }}>Short
                                                        Term
                                                    </option>
                                                    <option value="1" {{ old('duration') == 1 ? "selected" : "" }}>
                                                        Weekly
                                                    </option>
                                                    <option value="2" {{ old('duration') == 2 ? "selected" : "" }}>Long
                                                        Term
                                                    </option>
                                                </select>
                                            </div>

                                            @if ($errors->has('duration'))
                                                <span class="color--error"><strong>{{ $errors->first('duration') }}</strong></span>
                                            @endif
                                        </div>

                                        <div class="col-md-12" style="margin-bottom: 0;">
                                            <label>Rates (in &#8377;)</label>
                                        </div>

                                        <div class="col-md-4">
                                            <input placeholder="Short Term" type="number" value="{{ old('rate1') }}"
                                                   id="rate1" name="rate1">

                                            @if ($errors->has('rate1'))
                                                <span class="color--error"><strong>{{ $errors->first('rate1') }}</strong></span>
                                            @endif
                                        </div>

                                        <div class="col-md-4" style="display: none;">
                                            <input placeholder="Weekly" type="number" value="{{ old('rate2') }}"
                                                   id="rate2" name="rate2">

                                            @if ($errors->has('rate2'))
                                                <span class="color--error"><strong>{{ $errors->first('rate2') }}</strong></span>
                                            @endif
                                        </div>

                                        <div class="col-md-4" style="display: none;">
                                            <input placeholder="Long Term" type="number" value="{{ old('rate3') }}"
                                                   id="rate3" name="rate3">

                                            @if ($errors->has('rate3'))
                                                <span class="color--error"><strong>{{ $errors->first('rate3') }}</strong></span>
                                            @endif
                                        </div>

                                        <div class="col-md-12">
                                            <label>Address:</label>
                                            <input id="product_address" name="address" type="text" value="">
                                            <div id="product_latlng" class="hidden">
                                                <input name="lat" type="hidden" value="">
                                                <input name="lng" type="hidden" value="">
                                            </div>
                                        </div>

                                        <div id="pictures_div" class="col-md-12">
                                            <label>Pictures</label>
                                            <input class="dz-message" type="text" style="cursor: pointer;" disabled
                                                   data-dz-message value="Drag here or click here">
                                        </div>

                                        <div class="col-lg-3 col-md-4">
                                            <input type="submit" id="submit_post"
                                                   class="btn btn--primary type--uppercase" name="submit_post"
                                                   value="Post">
                                        </div>

                                        <div class="col-12">
                                            <div id="previews" class="col-md-12"
                                                 style="border-radius: 6px; border: 1px solid #ccc;">
                                                <div id="dropzone_template" class="col-6 col-md-4 align-items-center"
                                                     style="display: inline-block; padding: 10px;">
                                                    <div class="col-12"
                                                         style="text-align: center; padding-right: 0; margin-bottom: 10px;">
                                                        <img data-dz-thumbnail/>
                                                    </div>

                                                    <div class="col-12" style="padding: 0;">
                                                        <input class="btn btn-md" data-dz-remove value="Cancel"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </form>
                            </div>
                            <div id="account-inventory" style="@if($tab != 'inventory')display: none;@endif" class="account-tab"></div>
                            <div id="account-messages" style="@if($tab != 'messages')display: none;@endif" class="account-tab"></div>
                        </div>
                    </div>
                </div>
                <!--end of row-->
            </div>
            <!--end of container-->
        </section>
    </div>

    {{--Dropzone Plugin--}}
    <script src="{{ asset('js/dropzone.js') }}"></script>

    <script type="text/javascript">

        $(document).ready(function () {

            var token = '{{ csrf_token() }}';

            //js functions
            function ucfirst(string) {
                if(string.length > 0)
                    return string.charAt(0).toUpperCase() + string.slice(1);
                else
                    return string;
            }

            function ucwords (str) {

                return (str + '')
                    .replace(/^(.)|\s+(.)/g, function ($1) {
                        return $1.toUpperCase()
                    })
            }

            //js for URLs and tab change
            function changeTab(tab) {

                $('.account-tab').not('[style="display: none;"]').fadeOut( function () {
                    $('#account-' + tab + '.account-tab').fadeIn();
                });

            }

            $('.tab-link').on('click', function (e) {

                e.preventDefault();
                var tab = $(this).attr('id');
                tab = tab.substring(0, tab.length - 5);
                changeTab(tab);
                history.pushState({tab: tab}, 'here', '{{ URL::to('account') }}/' + tab);

            });

            window.onpopstate = function (ev) {

                if(ev.state) {
                    changeTab(ev.state.tab);
                }

            };

            @if( $fixhome )
                history.replaceState({ tab: 'profile'}, 'test', '{{ URL::to('account') }}/profile');
            @endif

            //js for Message Count
            function updateCount(count) {

                $('#message_count').html(count);
                if(count == 0)
                    $('#message_count').hide();
                else
                    $('#message_count').show();

            }

            //js for Messages
            var requestMessageTemplate = $('#request_message_template');
            requestMessageTemplate.remove();
            requestMessageTemplate = requestMessageTemplate.html();
            var replyMessageTemplate = $('#reply_message_template');
            replyMessageTemplate.remove();
            replyMessageTemplate = replyMessageTemplate.html();

            var date_options = {
                weekday: "long", year: "numeric", month: "short",
                day: "numeric"
            };

            $('#messages_modal_template').find('#view_messages').on('click', function (e) {

                e.preventDefault();
                $('.all-page-modals').find('.modal-container').fadeOut(200, 'swing', function () {

                    $('.all-page-modals').find('.modal-container').removeClass('modal-active').show();
                    $('#messages_link').trigger('click');

                });



            });

            function replySeen(tid) {

                var message_count = $('#message_count').html();
                updateCount(message_count - 1);
                $.ajax({

                    url: '{{ route('seen_message') }}',
                    type: 'POST',
                    data: { _token: token, tid: tid}

                });

            }

            function getContact(tid, message) {

                message.find('.contact_div').addClass('loading');
                $.ajax({

                    url: '{{ route('get_contact') }}',
                    type: 'POST',
                    data: { _token: token, tid: tid},
                    success: function (response) {

                        if(response.message === 'success') {
                            message.find('.contact').html(response.contact);
                            message.find('.contact_div').removeClass('loading');
                            if(response.hasOwnProperty('first_name')) {

                                message.find('.first_name').html(response.first_name);
                                message.find('.last_name').html(response.last_name);

                            }
                        }

                    }

                });

            }

            function getMessages(first_run)
            {

                var messages_div = $('#account-messages');
                messages_div.html('');
                messages_div.addClass('loading');

                $.ajax({

                    url: '{{ route('get_messages') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    success: function (response) {

                        var new_message_count = response.new_requests.length + response.new_replies.length;
                        var total_count = response.old_requests.length + response.new_requests.length + response.old_replies.length + response.new_replies.length;
                        if (total_count === 0) {
                            messages_div.html('<div class="row align-items-center" style="height: 400px;">' +
                                '<div class="col-12 text-center h5">You don\'t have any messages :(</div>' +
                                '</div>' +
                                '</div>');
                        }
                        else {
                            messages_div.append('<div class="row"><div class="tab-container col-md-12">' +
                                '<ul class="tabs"></ul>' +
                                '<ul class="tabs-content"></ul>' +
                                '</div></div>');
                            messages_div.find('ul.tabs').append('<li id="requests_title" class="active">' +
                                '<div class="tab__title"><span class="h5">Requests</span></div>' +
                                '</li>');
                            messages_div.find('ul.tabs-content').append('<li id="requests_content" class="active">' +
                                '<div class="tab__content"></div>' +
                                '</li>');
                            messages_div.find('ul.tabs').append('<li id="replies_title">' +
                                '<div class="tab__title"><span class="h5">Replies</span></div>' +
                                '</li>');
                            messages_div.find('ul.tabs-content').append('<li id="replies_content">' +
                                '<div class="tab__content"></div>' +
                                '</li>');
                            $('ul.tabs').find('li').on('click', function (target) {

                                $('ul.tabs').children('li.active').removeClass('active');
                                $('ul.tabs-content').children('li.active').removeClass('active');
                                $(this).addClass('active');
                                var tab = $(this).attr('id').replace(/_title/, '');
                                $('ul.tabs-content').children('#' + tab + '_content').addClass('active');

                            });
                            var requests_container = messages_div.find('#requests_content').find('.tab__content');
                            var replies_container = messages_div.find('#replies_content').find('.tab__content');
                            if(response.new_requests.length + response.old_requests.length === 0) {

                                requests_container.html('<div class="row align-items-center" style="height: 400px;">' +
                                    '<div class="col-12 text-center h5">You don\'t have any requests :(</div>' +
                                    '</div>' +
                                    '</div>');

                            }
                            if(response.new_replies.length + response.old_replies.length === 0) {

                                replies_container.html('<div class="row align-items-center" style="height: 400px;">' +
                                    '<div class="col-12 text-center h5">You don\'t have any replies :(</div>' +
                                    '</div>' +
                                    '</div>');

                            }
                            $.each(response.new_requests, function (i, d) {

                                var message = $(requestMessageTemplate);
                                message.find('.product_name').html(d.name);
                                message.find('.first_name').html(d.first_name);
                                message.find('.last_name').html(d.last_name);
                                message.find('.accepted_request').hide();
                                message.find('.rejected_request').hide();
                                message.find('.from_date').html(new Date(d.from_date).toLocaleDateString("en-us", date_options));
                                message.find('.to_date').html(new Date(d.to_date).toLocaleDateString("en-us", date_options));
                                message.find('.accept').on('click', function (e) {

                                    e.preventDefault();
                                    answerRequest(d.tid, 1, message);

                                });
                                message.find('.reject').on('click', function (e) {

                                    e.preventDefault();
                                    answerRequest(d.tid, 0, message);

                                });
                                requests_container.append(message);

                            });
                            $.each(response.old_requests, function (i, d) {

                                var message = $(requestMessageTemplate);
                                message.find('.response_buttons_div').remove();
                                message.find('.new_request').remove();
                                message.find('.product_name').html(d.name);
                                message.find('.first_name').html(d.first_name);
                                message.find('.last_name').html(d.last_name);
                                message.find('.from_date').html(new Date(d.from_date).toLocaleDateString("en-us", date_options));
                                message.find('.to_date').html(new Date(d.to_date).toLocaleDateString("en-us", date_options));
                                switch (parseInt(d.status)) {
                                    case 2:
                                        message.find('h4.product_name').after('<i class="fa fa-times-circle"></i>');
                                        message.find('.accepted_request').remove();
                                        break;
                                    case 4:
                                        message.find('h4.product_name').after('<i class="fa fa-times-circle"></i>');
                                        message.find('.rejected_request').remove();
                                        getContact(d.tid, message);
                                        message.find('.contact_div').show();
                                        break;
                                    case 3:
                                    case 5:
                                        message.find('h4.product_name').after('<i class="fa fa-check-circle"></i>');
                                        message.find('.rejected_request').remove();
                                        getContact(d.tid, message);
                                        message.find('.contact_div').show();
                                        break;
                                }
                                requests_container.append(message);

                            });
                            $.each(response.new_replies, function (i, d) {

                                var message = $(replyMessageTemplate);
                                message.find('.request_pending').remove();
                                message.find('.product_name').html(d.name);
                                message.find('.from_date').html(new Date(d.from_date).toLocaleDateString("en-us", date_options));
                                message.find('.to_date').html(new Date(d.to_date).toLocaleDateString("en-us", date_options));
                                if(d.status == 2) {

                                    message.find('.contact_div').remove();
                                    message.find('.request_accepted').remove();
                                    message.find('.response_buttons_div').find('.show_contact').remove();
                                    message.find('.response_buttons_div').find('.okay').on('click', function(e) {

                                        e.preventDefault();
                                        replySeen(d.tid);
                                        message.find('.response_buttons_div').slideUp(function () {
                                            $(this).remove();
                                        });

                                    });

                                } else {

                                    message.find('.request_rejected').remove();
                                    message.find('.response_buttons_div').find('.okay').remove();
                                    message.find('.response_buttons_div').find('.show_contact').on('click', function (e) {

                                        e.preventDefault();
                                        replySeen(d.tid);
                                        message.find('.response_buttons_div').slideUp(function () {

                                            getContact(d.tid, message);
                                            $(this).remove();

                                        });
                                        message.find('.contact_div').slideDown();

                                    });

                                }
                                replies_container.append(message);

                            });
                            $.each(response.old_replies, function (i, d) {

                                var message = $(replyMessageTemplate);
                                message.find('.response_buttons_div').remove();
                                message.find('.request_rejected').remove();
                                switch(parseInt(d.status)) {

                                    case 1:
                                        message.find('.request_accepted').remove();
                                        message.find('.contact_div').remove();
                                        break;
                                    case 3:
                                        message.find('.request_pending').remove();
                                        getContact(d.tid, message);
                                        message.find('.contact_div').show();
                                        break;
                                    case 4:
                                        message.find('.request_pending').remove();
                                        getContact(d.tid, message);
                                        message.find('.contact_div').show();
                                        break;
                                    case 5:
                                        message.find('.request_pending').remove();
                                        getContact(d.tid, message);
                                        message.find('.contact_div').show();
                                        break;

                                }
                                message.find('.product_name').html(d.name);
                                message.find('.from_date').html(new Date(d.from_date).toLocaleDateString("en-us", date_options));
                                message.find('.to_date').html(new Date(d.to_date).toLocaleDateString("en-us", date_options));
                                replies_container.append(message);

                            });
                        }

                        updateCount(new_message_count);
                        if(first_run) {
                            if(new_message_count !== 0) {
                                @if( $tab != 'messages')
                                $('#messages_modal_template').find('.modal-trigger').trigger('click');
                                @endif
                            }
                        }
                        messages_div.removeClass('loading');
                    }
                });
            }

            $('#messages_link').on('click', function () {

                getMessages(false);

            });

            function answerRequest(tid, reply, message) {

                var csrf_token = '{{ csrf_token() }}';
                $.ajax({

                    type: 'POST',
                    url: '{{ route('answer_request') }}',
                    data: {_token: csrf_token, tid: tid, reply: reply},
                    success: function (response) {

                        if(reply === 1) {

                            message.find('h4.product_name').after('<i class="fa fa-check-circle"></i>');
                            message.find('.accepted_request').slideDown();
                            message.find('.new_request').slideUp(function () {

                                message.find('.new_request').remove();
                                message.find('.rejected_request').remove();

                            });
                            getContact(tid, message);
                            message.find('.response_buttons_div').slideUp(function () {

                                message.find('.response_buttons_div').remove();
                                message.find('.contact_div').slideDown();

                            });

                        } else {

                            message.find('h4.product_name').after('<i class="fa fa-times-circle"></i>');
                            message.find('.new_request').slideUp(function () {

                                message.find('.new_request').remove();
                                message.find('.accepted_request').remove();

                            });
                            message.find('.rejected_request').slideDown();
                            message.find('.response_buttons_div').remove();
                            message.find('.response_buttons_div').slideUp(function () {

                                message.find('.response_buttons_div').remove();

                            });

                        }
                        updateCount(message_count - 1);
                    }

                });

            }

            getMessages(true);

            //js for Notifciations
            var notificationTemplate = $('#notifications_template');
            notificationTemplate.remove();
            notificationTemplate = notificationTemplate.html();

            function setCloseNotification(notification) {

                notification.fadeOut(function () {

                    notification.attr('style', '');
                    notification.addClass('notification--dismissed');
                    getNotifications();

                })

            }

            function replyForNotification(id, reply) {

                var csrf_token = '{{ csrf_token() }}';
                $.ajax({

                    type: 'POST',
                    url: '{{ route('reply_notification') }}',
                    data: {_token: csrf_token, id: id, reply: reply},

                });

            }

            function getNotifications() {

                $.ajax({

                    type: 'GET',
                    url: '{{ route('get_notification') }}',
                    dataType: 'JSON',
                    success: function (returned_data) {

                        if(!(returned_data == null)) {
                            $('.notification.notification--reveal').remove();
                            var notification = $(notificationTemplate);
                            notification.find('#product_name').html(returned_data.name);
                            notification.find('#product_image').attr('src', '{{ asset('img/uploads/products/small') }}/' + returned_data.image);
                            notification.find('#renter_name').html(ucfirst(returned_data.first_name) + ' ' + ucfirst(returned_data.last_name));
                            $('body').append(notification);
                            $('#notification_trigger').trigger('click');

                            notification.find('#yes_close_notification').on('click', function () {

                                setCloseNotification(notification);
                                replyForNotification(returned_data.tid, 1);

                            });

                            notification.find('#no_close_notification').on('click', function () {

                                setCloseNotification(notification);
                                replyForNotification(returned_data.tid, 0);

                            });
                        }
                    }
                });

            }

            getNotifications();

            //js for loading inventory
            function updateAvailability(id, availability) {

                var csrf = '{{ csrf_token() }}';
                $.ajax({

                    type: 'POST',
                    url: '{{ route('update_availability') }}',
                    dataType: 'JSON',
                    data: {_token: csrf, product_id: id, availability: availability},
                    success: function () {

                        console.log('success');

                    }
                });

            }

            function loadInventory() {

                var inventory_div = $('#account-inventory');
                inventory_div.html('');
                inventory_div.addClass('loading');
                $.ajax({

                    type: 'GET',
                    url: '{{ route('get_inventory') }}',
                    dataType: 'JSON',
                    success: function (returned_data) {

                        inventory_div.removeClass('loading');
                        if (returned_data.length == 0) {
                            inventory_div.html('<div class="row align-items-center" style="height: 400px;">' +
                                '<div class="col-12 text-center h5">You havent uploaded any products :(</div>' +
                                '</div>' +
                                '</div>');
                        }
                        else {
                            inventory_div.html('<div class="row">' +
                                '<div class="col-md-12">' +
                                '<div class="masonry">' +
                                '<div class="masonry__container masonry--active row"></div>' +
                                '</div>' +
                                '</div>' +
                                '</div>');
                            var masonry_container = inventory_div.find('.masonry__container');
                            var loading_url = '{{ asset('img/loading.gif') }}';
                            $.each(returned_data, function (i, d) {

                                var image = '{{ asset('img/uploads/products/small') }}/' + d.image;
                                var product_link = '{{ URL::to('account/inventory') }}' + '/' + d.id;
                                var product = $('<div class="masonry__item col-6 col-lg-4">' +
                                    '   <div class="product">' +
                                    '       <span class="product_id_info hidden">' + d.name + '</span>' +
                                    '       <a class="product_link" href="' + product_link + '">' +
                                    '           <img class="img-fluid" style="border-radius: 5px;" alt="Image" id="" data-src="' + image + '" src="' + loading_url + '"/>' +
                                    '       </a>' +
                                    '       <a class="block product_link" href="' + product_link + '">' +
                                    '           <div class="text-center"><h5>' + d.name + '</h5></div>' +
                                    '       </a>' +
                                    '       <form>' +
                                    '       <div class="col-md-12">' +
                                    '           <div class="input-checkbox input-checkbox--switch">' +
                                    '               <input type="checkbox" ' + ((d.availability == '1')? 'checked ':'') + 'name="public-profile" id="checkbox-' + i + '">' +
                                    '               <label for="checkbox-' + i + '"></label>' +
                                    '           </div>' +
                                    '           <span>Availability</span>' +
                                    '       </div>' +
                                    '       </form>' +
                                    '   </div>' +
                                    '</div>');
                                masonry_container.append(product);
                                product.find('input[type="checkbox"]').on('change', function () {

                                    if(product.find('input[type="checkbox"]').is(":checked"))
                                        updateAvailability(d.id, 1);
                                    else
                                        updateAvailability(d.id, 0);

                                })

                            });
                        }
                        $('.product').find('img').each(function () {
                            $(this).attr('src', $(this).attr('data-src'));
                        });

                    }

                });

            }

            $('#inventory_link').on('click', function () {

                loadInventory();

            });

            @if( $tab == 'inventory' )
                loadInventory();
            @endif

            //js for duration and rates
            function changeRequiredStates() {

                var rate1 = $('#rate1');
                var rate2 = $('#rate2');
                var rate3 = $('#rate3');
                switch ($('#duration').val()) {
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

            changeRequiredStates();
            $('#duration').on('change', function () {
                changeRequiredStates();
            });

            //js for Address
            var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(19.296441, 72.9864994),
                new google.maps.LatLng(18.8465126, 72.9042434)
            );

            $("#user_address").geocomplete({
                location: '{{ Auth::user()->address }}',
                details: "#user_latlng",
                bounds: defaultBounds
            }).bind("geocode:result", function (event, result) {
                console.log(result);
            });

            $("#product_address").geocomplete({
                location: '{{ Auth::user()->address }}',
                details: "#product_latlng",
                bounds: defaultBounds
            }).bind("geocode:result", function (event, result) {
                console.log('Product :' + result);
            });

            //js for alerts
            $('#alert').parent().slideDown();
            $('.fa-close').on('click', function (e) {
                $(this).parent().parent().slideUp();
            });

            //js for subcategories
            var category_selector = $('#lend-form').find($('#category_id'));

            function changeSubcategories() {

                var subcategory_selector = $('#lend-form').find($('#subcategory_id'));
                subcategory_selector.empty();
                var category_id = category_selector.val();
                var csrf_token = '{{ csrf_token() }}';

                subcategory_selector.empty();
                subcategory_selector.append('<option value="" selected disabled>Select a subcategory</option>');

                if (category_id != '') {
                    $.ajax(
                        {
                            type: 'POST',
                            url: '{{ route('get_subcategories') }}',
                            data: {_token: csrf_token, category_id: category_id},
                            dataType: 'JSON',
                            success: function (returned_data) {

                                $.each(returned_data, function (i, d) {

                                    subcategory_selector.append('<option value="' + d.id + '">' + ucwords(d.name) + '</option>');

                                });

                            }
                        }
                    );
                }
            }

            changeSubcategories();
            category_selector.on('change', function () {
                changeSubcategories();
            })

        });

        //js for Dropzone
        var previewNode = document.querySelector("#dropzone_template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        Dropzone.options.lendForm = {

            autoProcessQueue: false,
            uploadMultiple: true,
            parallelUploads: 8,
            previewTemplate: previewTemplate,
            previewsContainer: '#previews',
            maxFiles: 8,
            acceptedFiles: 'image/*',

            init: function () {
                var myDropzone = this;
                var files = 0;

                $('#lend-form').submit(function (e) {
                    // Make sure that the form isn't actually being sent.
                    e.preventDefault();
                    e.stopPropagation();
                    myDropzone.processQueue();
                    if (files == 0) {
                        $('#pictures_error').remove();
                        $('#pictures_div').append('<span id="pictures_error" style="display: none;" class="color--error"><strong>Upload atleast one picture.</strong></span>');
                        $('#pictures_error').fadeIn();
                    }

                });

                this.on("addedfile", function (file) {
                    files++;
                });
                this.on("removedfile", function (file) {
                    files--;
                });

                this.on("successmultiple", function (files, response) {
                    window.location = '{{ route('account', ['inventory']) }}';
                });
                this.on("errormultiple", function (files, response) {
                    location.reload();
                });
            }

        };


    </script>

@endsection
