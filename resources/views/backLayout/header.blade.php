  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>@yield('title')| </title>
    <link rel="shortcut icon" href="https://laravel.com/img/favicon/favicon.ico" />


    <!-- Bootstrap -->
  <link href="{{ URL::asset('/backend/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="{{ URL::asset('/backend/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="{{ URL::asset('/backend/build/css/custom.min.css') }}" rel="stylesheet">
  <!-- Datatables -->
  <link rel="stylesheet" href="{{ URL::asset('/backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('/backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}">
  @yield('style')

  <style>
    .panel{margin-top: 60px;}
    .col-md-55, .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
      /*padding: 5px 5px 5px 5px !important;*/
    }
    .SumoSelect .select-all{height:auto !important;}

  </style>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">