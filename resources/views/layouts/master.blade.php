
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{asset('img/icon_64.png')}}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script src="{{ asset('js/app.js') }}" defer></script>

    {{-- <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet"> --}}

</head>
<body class="hold-transition sidebar-mini text-sm">
    
    {{ Auth::user() }}
    <div id="app">
        <app-dashboard></app-dashboard>
    </div>

    <script>
        @auth
            window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
        @else
            window.Permissions = [];
        @endauth
    </script>
      
</body>
</html>
