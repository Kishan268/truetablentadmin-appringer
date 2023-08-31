<!DOCTYPE html>
@langrtl
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@endlangrtl

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', app_name())">
    <meta name="author" content="@yield('meta_author', app_name())">
    <meta name="_token" content="{{ csrf_token() }}">
    @yield('meta')
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->
    <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
    <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net/dataTables.bootstrap4.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.css" />
    {{-- <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css'> --}}
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" ></script> --}}

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> --}}

    {{-- <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
 --}}
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" /> --}}
    {{-- <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" /> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">


    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    @stack('plugin-styles')
    <!-- common css -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    @stack('before-styles')

    {{-- {{ style(mix('css/backend.css')) }} --}}
    <style type="text/css">
        .colorRed {
            margin-right: -5px;
            color: #eb2622;
        }

        .colorBlue {
            margin-right: -9px;
            color: #4488ee;
        }

        th.sortable {
            position: relative;
            cursor: pointer;
        }

        th.sortable::after {
            font-family: FontAwesome;
            content: "\f0dc";
            position: absolute;
            right: 8px;
            color: #999;
        }

        th.sortable.asc::after {
            content: "\f0d8";
        }

        th.sortable.desc::after {
            content: "\f0d7";
        }

        th.sortable:hover::after {
            color: #333;
        }
        .spinner-border {
          color:#14BC9A;
          text-align: center;
          font-family: 'PT Sans Narrow', sans-serif;
          font-size: 25px;
        }
        
        /*table.spinner-border tbody {
            position: relative;
        }

        table.loading tbody:after {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.1);
            background-image: url(data:image/gif;base64,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);
            background-position: center;
            background-repeat: no-repeat;
            background-size: 50px 50px;
            content: "";
        }*/

        /* Closed submenu icon */
        .nav-link[aria-expanded="false"] .submenu-icon::after {
            content: " \f0d7";
            font-family: FontAwesome;
            display: inline;
            text-align: right;
            padding-left: 10px;
        }

        /* Opened submenu icon */
        .nav-link[aria-expanded="true"] .submenu-icon::after {
            content: " \f0da";
            font-family: FontAwesome;
            display: inline;
            text-align: right;
            padding-left: 10px;
        }

        .truncate-1 {
            white-space: pre-line;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
            overflow: hidden;
            word-wrap: break-word;
        }

        .add-job .form-control-label {
            font-size: 1em;
            font-weight: 600;
        }

        .add-job .input-lebel {
            font-size: 1.1em;
            font-weight: 600;
        }

        .add-job .form-check-label {
            font-size: 1.1em;
            font-weight: 600;
            color: #747A8D;
            white-space: nowrap;
        }

        .add-job .form-check {
            padding-left: 2.5em;
            margin-bottom: 0.125rem;
            min-width: 33%;
        }

        .required:after {
            content: " *";
            color: red;
        }


        .switch {
            position: relative;
            display: inline-block;
            width: 3.438em;
            height: 1.563em;
            margin: auto;
            margin-left: 1em;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: 0.4s;
            transition: 0.4s;
            margin-bottom: 0px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 1.188em;
            width: 1.188em;
            left: 0.25em;
            bottom: 0.188em;
            background-color: white;
            -webkit-transition: 0.4s;
            transition: 0.4s;
        }

        input:checked+.slider {
            background-color: #14bc9a;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196f3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(1.625em);
            -ms-transform: translateX(1.625em);
            transform: translateX(1.625em);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }


        .input-lebel-switch {
            font-size: 1.25em;
            font-weight: 600;
            color: #11263c;
            // min-width: 45%;
        }

        .range_label {
            display: flex;
            justify-content: space-between;
            font-size: 1em;
            color: var(--gray6);
            font-weight: 600;
        }

        .green-btn {
            background-color: #14bc9a;
            color: #fff;
            border: 0;
            outline: none;
            padding: 0.313em 1.25em;
            font-family: "Plus Jakarta Sans", sans-serif;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 500;
        }

        table.dataTable {
            font-weight: normal !important;
            font-size: 14px !important;
        }

        table.table {
            font-weight: normal !important;
            font-size: 14px !important;
        }

        td {
            vertical-align: middle;
        }

        /* th.sortable::after {
            margin-right: 15px;
        }
        th.sortable::before {
            margin-right: 15px;
        }*/
        /*a:hover {
            color: #14BC9A !important;
            text-decoration: none;
        }*/

        .hand-hover {
            cursor: pointer;
        }

        label.error {
            color: red;
        }

        /*.side-icon {
            width: 15px;
            height: auto;
        }*/
        @media screen and (max-width: 650px) {
            li.page-item {
                display: none;
            }

            .page-item:first-child,
            .page-item:nth-child(3),
            .page-item:nth-last-child(3),
            .page-item:last-child,
            .page-item.active,
            .page-item.disabled {
                display: block;
            }
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #14BC9A;
            color: #fff;
            border-color: #14BC9A;
            padding: 1px 17px;
            border-radius: 0.15rem;
            margin-top: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: black !important;
        }

        button.select2-selection__choice__remove {
            background-color: #14BC9A !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #ffff;
        }
        textarea.select2-search__field {
            margin-bottom: 6px !important;
        }
    </style>
    @stack('after-styles')
</head>

<body class="app header-fixed sidebar-fixed aside-menu-off-canvas sidebar-lg-show">

    <script src="{{ asset('assets/js/spinner.js') }}"></script>

    <div class="main-wrapper" id="app">

        @include('backend.includes.sidebar')
        <div class="page-wrapper">
            @include('backend.includes.header')
            @include('includes.partials.read-only')
            @include('includes.partials.logged-in-as')
            <div class="content-header">
                @yield('page-header')
            </div>
            <!--content-header-->
            <div class="page-content">
                @include('includes.partials.messages')
                @yield('content')
            </div>
            @include('backend.includes.aside')
            @include('backend.includes.footer')
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <script src="{{ asset('assets/js/template.js') }}"></script>
    {{-- <script src="{{ asset('assets/plugins/sweetalert2/sweetalt2.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/sweet-alert.js') }}"></script> --}}
    {{-- @stack('custom-scripts') --}}
    {{-- <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script> --}}
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/custome-swal.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('assets/plugins/chartjs/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/chartjs.js') }}"></script>

    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

    <!-- <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script> -->

    <script type="text/javascript">
        $(document).ready(function() {
            $('.example-table').DataTable();
        });
        $(function() {
            $(".datepicker").datepicker({
                dateFormat: "dd-mm-yy"
            });
        });
        setTimeout(function() {
            $('.alert-success').css('display', 'none');
            $('.alert-danger').css('display', 'none');
        }, 3000); // <-- time in milliseconds
    </script>

</body>

</html>
