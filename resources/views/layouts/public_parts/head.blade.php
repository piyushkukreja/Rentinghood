@section('head')
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Rent anything, right from your neighbourhood">
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}"/>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/stack-interface.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/socicon.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/iconsmind.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/flickity.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" media="all"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:200,300,400,400i,500,600,700%7CMerriweather:300,300i"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
          integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
@endsection