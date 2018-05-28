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

        @yield('navbar')

        @yield('content')

        @yield('footer')

        @yield('scripts')

    </body>

</html>
