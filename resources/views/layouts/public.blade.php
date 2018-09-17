@include('layouts.public_parts.head')
@include('layouts.public_parts.footer')
@include('layouts.public_parts.scripts')
<!doctype html>
<html lang="en">
    <head>
        @yield('head')
    </head>
    <body class="" data-smooth-scroll-offset="64">
        <a id="start"></a>

        @auth
            @if(Auth::user()->isAdmin())
                <a id="admin-panel-button" href="{{ route('admin.index') }}" class="panel-buttons" data-tooltip="Admin Dashboard">
                    <img src="{{ asset('admin/img/icons/admin.png') }}">
                </a>
            @endif
            @if(Auth::user()->isVendor())
                <a id="vendor-panel-button" href="{{ route('vendor.index') }}" class="panel-buttons" data-tooltip="Vendor Dashboard">
                    <img src="{{ asset('admin/img/icons/vendor.png') }}">
                </a>
            @endif
        @endauth

        @yield('navbar')

        @yield('content')

        @yield('footer')

        @yield('scripts')

    </body>

</html>
