<!-- Meta Tag -->
@yield('meta')

<!-- Title Tag  -->
<title>@yield('title')</title>

<!-- Favicon -->
<link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

<!-- Web Font -->
<link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,400,500,600,700,800,900&display=swap" rel="stylesheet">

<!-- Manifest -->
<link rel="manifest" href="{{ asset('manifest.json') }}">

<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ request()->secure() ? secure_asset('frontend/css/bootstrap.css') : asset('frontend/css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/jquery.fancybox.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/themify-icons.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/niceselect.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/animate.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/flex-slider.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/owl-carousel.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/slicknav.min.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/jquery-ui.css') }}">

<!-- Main CSS -->
<link rel="stylesheet" href="{{ asset('frontend/css/reset.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('frontend/css/responsive.css') }}">

<style>
    /* Multilevel dropdown */
    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu>a:after {
        content: "\f0da";
        float: right;
        border: none;
        font-family: 'FontAwesome';
    }

    .dropdown-submenu>.dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: 0px;
        margin-left: 0px;
    }
</style>

@stack('styles')