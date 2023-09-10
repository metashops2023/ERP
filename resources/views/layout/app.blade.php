<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>@yield('title') MetaShops</title>
    <!-- Icon -->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <link rel="stylesheet" href="{{ asset('/backend/asset/css/fontawesome/css/all.css')}}">
    <link rel="stylesheet" href="{{ asset('/backend/asset/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('/backend/asset/css/selectize.css')}}">
    <link rel="stylesheet" href="{{ asset('/backend/asset/css/dropzone.css')}}">

    <link href="{{ asset('/backend/css/reset.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/typography.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/body.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/shCore.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/jquery.jqplot.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/jquery-ui-1.8.18.custom.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/data-table.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/form.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/ui-elements.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/wizard.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/sprite.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('/backend/css/gradient.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('/backend/asset/css/comon.css') }} ">
    <link rel="stylesheet" href="{{ asset('/backend/asset/css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('/backend/asset/css/style.css') }}">


</head>

<body>
    @yield('content')
</body>

</html>
