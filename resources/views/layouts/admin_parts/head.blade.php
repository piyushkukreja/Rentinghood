@section('head')

    <head>
        <meta charset="utf-8" />
        <title>{{ config('app.name', 'Laravel') . (isset($data['title']) ? ' - ' . $data['title'] : '') }}</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #4 for blank page layout" name="description" />
        <meta content="" name="author" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/css/font-awesome.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/css/simple-line-icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/css/bootstrap-switch.css') }}" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <link href="{{ asset('admin/css/datatables.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/css/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="{{ asset('admin/css/components.css') }}" rel="stylesheet" id="style_components" type="text/css" />
        <link href="{{ asset('admin/css/plugins.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->

        <!--Full calendar-->
        <link rel="stylesheet" href="{{asset('admin/css/fullcalendar.css')}} ">
        <link rel="stylesheet" href="{{asset('admin/css/fullcalendar.min.css')}}">

        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="{{ asset('admin/css/layout.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('admin/css/default.css') }}" rel="stylesheet" type="text/css" id="style_color" />
        <link href="{{ asset('admin/css/custom.css') }}" rel="stylesheet" type="text/css" />
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" /> </head>
    <style>
        .page-sidebar .page-sidebar-menu > li > a > i,
        .page-sidebar-closed.page-sidebar-fixed .page-sidebar:hover .page-sidebar-menu > li > a > i {
            font-size: 1.5em;
        }
    </style>

@endsection