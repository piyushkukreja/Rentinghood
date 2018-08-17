@include('layouts.vendor_parts.head')
@include('layouts.vendor_parts.navbar')
@include('layouts.vendor_parts.sidebar')
@include('layouts.vendor_parts.footer')
@include('layouts.vendor_parts.scripts')

<!doctype html>
<html lang="{{ app()->getLocale() }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

@yield('head')

<body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo">

@yield('navbar')

<div class="page-container">

@yield('sidebar')

<!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
    @yield('content')
    <!-- END CONTENT BODY -->
    </div>
    <!-- END CONTENT -->

</div>

@yield('footer')

@yield('scripts')

</body>
</html>
