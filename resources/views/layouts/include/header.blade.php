<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="path" content="{{ url('/') }}">

    <title>@yield('title')</title>
    <!-- dataTable css -->
    <link href="{{ asset('/')}}assets/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/')}}assets/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/')}}assets/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/')}}assets/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/')}}assets/css/scroller.bootstrap.min.css" rel="stylesheet">
    
    <link href="{{ asset('/')}}assets/css/select2.min.css" rel="stylesheet">

    <link href="{{ asset('/')}}assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('/')}}assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="{{ asset('/')}}assets/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
    <!-- Toastr style -->
    <link href="{{ asset('/')}}assets/css/plugins/toastr/toastr.min.css" rel="stylesheet">
    <link href="{{ asset('/')}}assets/css/plugins/iCheck/custom.css" rel="stylesheet">
    <!-- Gritter -->
    <link href="{{ asset('/')}}assets/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">

    <link href="{{ asset('/')}}assets/css/animate.css" rel="stylesheet">
    <link href="{{ asset('/')}}assets/css/style.css" rel="stylesheet">
    <link href="{{ asset('/')}}assets/css/sweetalert.css" rel="stylesheet">

    <script src="{{ asset('/')}}assets/js/jquery-3.1.1.min.js"></script>
    <link href="{{ asset('/')}}assets/css/plugins/footable/footable.core.css" rel="stylesheet">
    <link href="{{ asset('/')}}assets/css/plugins/footable/footable.core.css" rel="stylesheet">

    
    <script type="text/javascript">
        @include('sweetalert::alert')
    </script>

</head>

<body class="">