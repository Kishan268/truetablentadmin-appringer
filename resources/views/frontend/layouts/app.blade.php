<!DOCTYPE html>
<!--
Template Name: NobleUI - Laravel Admin Dashboard Template
Author: NobleUI
Purchase: https://1.envato.market/nobleui_laravel
Website: https://www.nobleui.com
Portfolio: https://themeforest.net/user/nobleui/portfolio
Contact: nobleui123@gmail.com
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html>
<head>
  <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', app_name())</title>

        <meta name="description" content="@yield('meta_description', app_name())">
        <meta name="author" content="@yield('meta_author', app_name())">
        <link rel="icon" href="{{ asset('/img/favicon.png') }}" type="image/x-icon" />
        @yield('meta')
      <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
      <!-- plugin css -->
      <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
      <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
      <!-- end plugin css -->
      @stack('plugin-styles')
      <!-- common css -->
      <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
      <!-- end common css -->
      @stack('style')
</head>
<body data-base-url="{{url('/')}}">
  <script src="{{ asset('assets/js/spinner.js') }}"></script>
   <div class="main-wrapper" id="app">
      <div class="page-wrapper full-page">
        @include('frontend.includes.header')
        @yield('content')
      </div>
        @include('backend.includes.footer')

    </div>
    <!-- base js -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <!-- end base js -->
    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->
    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <!-- end common js -->
    @stack('custom-scripts')
</body>
</html>