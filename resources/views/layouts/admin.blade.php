<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name'.' | Dashboard', 'AdminLTE 3 | Dashboard') }}</title>
        <link rel="icon" href="{{ asset('backend/img/sweeklyplan_logo.jpg') }}" type="image/icon type">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <link rel="stylesheet" href="{{ asset('backend/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        {{-- style --}}
        @livewireStyles

        <link rel="stylesheet" href="{{ asset('backend/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- JQVMap -->
        <link rel="stylesheet" href="{{ asset('backend/plugins/jqvmap/jqvmap.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('backend/css/adminlte.min.css') }}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ asset('backend/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ asset('backend/plugins/daterangepicker/daterangepicker.css') }}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{ asset('backend/plugins/summernote/summernote-bs4.min.css') }}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <!-- for DataTables plug-in -->
        <link rel="stylesheet" href="{{ asset('backend/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('backend/css/buttons.dataTables.min.css') }}">
        {{-- sortable-tablesort --}}
        {{-- <link href="https://cdn.jsdelivr.net/gh/tofsjonas/sortable@latest/sortable.min.css" rel="stylesheet" /> --}}
        @yield('style')
        <style>
            body{
                font-family: 'amiri';
                font-weight: bold;
            }
        </style>
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

            <!-- Preloader -->
            {{-- <div class="preloader flex-column justify-content-center align-items-center">
                <img class="animation__shake" src="{{ asset('backend/img/AdminLTELogo.png') }}" alt="AdminLTELogo" height="60" width="60">
            </div> --}}

            <!-- Navbar -->
            @include('partial.backend.navbar')
            <!-- Main Sidebar Container -->
            @include('partial.backend.sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                {{ $slot }}
            </div>

            {{-- footer --}}
            @include('partial.backend.footer')

            {{-- modal Logout --}}
            @include('partial.backend.modal')

        </div>

        <!-- Scripts -->
        @livewireScripts
        <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>

        <!-- jQuery -->
        <script src="{{ asset('backend/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('backend/js/adminlte.min.js') }}"></script>
        {{-- <script src="{{ asset('backend/js/demo.js')}}"></script> ********--}}
        <!-- Select2 -->
        <script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>
        @yield('alpine-plugins')
        <!-- Alpine Core -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <!-- SweetAlert2 -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <x-livewire-alert::scripts />
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>
        {{-- sortable-tablesort --}}
        <script src="https://cdn.jsdelivr.net/gh/tofsjonas/sortable@latest/sortable.min.js"></script>
        @yield('script')
        @livewireChartsScripts
    </body>
</html>
