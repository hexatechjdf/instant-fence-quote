<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') - Instant Fence Price </title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- DataTables -->
    <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Sweet Alert -->
    <link href="{{ asset('plugins/sweet-alert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('plugins/animate/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('plugins/dropify/css/dropify.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/huebee@2/dist/huebee.min.css">

    <!-- Select2 -->
    <link href="{{ asset('plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Datepicker -->
    {{-- <link rel="stylesheet" type="text/css" href="jquery.datetimepicker.css" / > --}}
    <link href="{{ asset('plugins/timepicker/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">

    <!-- Toastr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <!-- Autocomplete jQuery -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.css"
        rel="stylesheet">

    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app-top.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css"
        integrity="sha512-gxWow8Mo6q6pLa1XH/CcH8JyiSDEtiwJV78E+D+QP0EVasFs8wKXq16G8CLD4CJ2SnonHr4Lm/yY2fSI2+cbmw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        #toast-container>.toast-error {
            background-color: #BD362F;
        }

        .iti.iti--allow-dropdown.iti--separate-dial-code {
            width: 100%;
        }

        .metismenu circle,
        section circle {
            fill: unset !important;
        }

    </style>
    <style>
        #toast-container>.toast-success {
            background-color: #5cb85c;
        }

        ul.metismenu.left-sidenav-menu.slimscroll.in.mm-show {
            max-height: 70vh !important;
            overflow: auto !important;
        }

    </style>
    <style>
        /*.left-sidenav-menu .active  {*/
        /*    background-color: #6d83e6;*/
        /*}*/

        .left-sidenav-menu>li>a.active {
            background-color: #6d83e6 !important;
            color: white;
        }

        .submenu li a.active {
            background: #6d83e6;
            color: white;
        }

        table.dataTable {
            width: 100% !important;
        }

        table#datatable {
            width: 100% !important;
        }

    </style>

    <style>
        /* Absolute Center Spinner */

        .loading {

            text-shadow: none;
            background-color: transparent;
            white-space: pre-wrap;
            position: fixed;
            z-index: 99999999999999999;
            height: 2em;
            width: 2em;
            overflow: show;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        /* Transparent Overlay */
        .loading:not(.loading-text):before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));
            background: -webkit-radial-gradient(rgba(20, 20, 20, .8), rgba(0, 0, 0, .8));

        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required):not(.loading-text) {
            /* hide "loading..." text */
            font-size: 40px;
            color: black;
            text-shadow: none;
            background-color: transparent;
            white-space: pre-wrap;
            border: 0;
        }

        .loading:not(:required):not(.loading-text):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 150ms infinite linear;
            -moz-animation: spinner 150ms infinite linear;
            -ms-animation: spinner 150ms infinite linear;
            -o-animation: spinner 150ms infinite linear;
            animation: spinner 150ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(255, 255, 255, 0.75) 1.5em 0 0 0, rgba(255, 255, 255, 0.75) 1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) 0 1.5em 0 0, rgba(255, 255, 255, 0.75) -1.1em 1.1em 0 0, rgba(255, 255, 255, 0.75) -1.5em 0 0 0, rgba(255, 255, 255, 0.75) -1.1em -1.1em 0 0, rgba(255, 255, 255, 0.75) 0 -1.5em 0 0, rgba(255, 255, 255, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                project -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

    </style>

    <style>
        #loadingg {
            width: 100%;
            height: 100%;
            top: 0px;
            left: 0px;
            position: fixed;
            display: block;
            z-index: 99
        }

        #loading-image {
            position: absolute;
            top: 33%;
            left: 46%;
            z-index: 100
        }

        .loading-overlay {
            background: rgb(0, 0, 0);
            opacity: 0.5;
            filter: alpha(opacity=50);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999999999999999999999;
        }

        .page-content-tab {
            padding: 0px !important;
        }

        /* hide the page untill #loadingg */

    </style>

    {{-- custom css  --}}
    <style>
        {
             ! ! setting('dashboard_css', 1) ?? '' ! !
        }

    </style>

    @yield('css')
</head>

<body data-layout="horizontal">
    <div class="loading">
        <span class="loading-text">Loading&#8230;</span>
    </div>



    <!-- Left Sidenav -->
    {{-- <div} class="left-sidenav">
        <!-- LOGO -->

        <!--end logo-->
        <div>
            @include('admin.components.left-nav-header')
        </div>

        @include('admin.components.nav')
    </div> --}}
    <!-- Top Bar Start -->
    @include('admin.components.topbar.init')
    <!-- Top Bar End -->
    <div class="page-wrapper">
        <!-- Page Content-->
        <div class="page-content-tab">
            <div class="container-fluid">
                @yield('content')
            </div><!-- container -->
            <!--<footer class="footer text-center text-sm-left">-->
            <!--    <div class="boxed-footer">-->
            <!--       {{ setting('software_name',1) }} &copy; {{ date('Y') }}-->
            <!--    </div>-->
            <!--</footer>-->
            <!--end footer-->
        </div>
        <!-- end page content -->
    </div>


    <form action="{{ route('logout') }}" method="POST" id="logout-form">@csrf</form>
    {{-- <div class="loading">Loading&#8230;</div> --}}


    <!-- jQuery  -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap5.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/metismenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/waves.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>

    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Sweet-Alert  -->
    <script src="{{ asset('plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('/plugins/dropify/js/dropify.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/tinymce/tinymce.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <!-- Datepicker  -->
    {{-- <script src="/build/jquery.datetimepicker.full.min.js"></script> --}}
    <script src="{{ asset('plugins/moment/moment.js') }}"></script>
    <script src="{{ asset('plugins/timepicker/bootstrap-material-datetimepicker.js') }}"></script>

    <!-- Autocomplete jQuery  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.js"></script>

    <script src="https://unpkg.com/huebee@2/dist/huebee.pkgd.min.js"></script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/libphonenumber-js/0.4.42/libphonenumber-js.min.js"
        integrity="sha512-5fM9vV2IoyEJcKl+ji79bZjIg15AOCRk/mheeC4EcBH2gHfjqzXHO/4pBYFHsNxyzQ30v6FdHbrnttblZa0SLw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"
        integrity="sha512-+gShyB8GWoOiXNwOlBaYXdLTiZt10Iy6xjACGadpqMs20aJOoh+PJt3bwUVA6Cefe7yF7vblX6QwyXZiVwTWGg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.min.js"
        integrity="sha512-jEc69+XeOdfDwLui+HpPWl8/8+cxkHcwcznwbVGrmVlECJD+L1yN0PljgF2MPs6+1bTX+gNvo/9C3YJ7n4i9qw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var input = document.querySelector("#phone");
        if (input) {
            intlTelInput(input, {
                //default US
                initialCountry: "US",
                separateDialCode: true,
            });
        }


        var elems = document.querySelectorAll('.color-input');
        for (var i = 0; i < elems.length; i++) {
            var elem = elems[i];
            var hueb = new Huebee(elem, {
                notation: 'hex',
            });
        }

    </script>



    @if (session('success'))
    <script>
        toastr.success("{{ session('success') }}", {
            timeOut: 10000
        })

    </script>
    @endif
    @if (session('error'))
    <script>
        toastr.error("{{ session('error') }}")

    </script>
    @endif

    <script>
        $(".dropify").dropify();
        $(".select2").select2({
            width: '100%'
        });
        $(".datepicker").bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            format: 'DD-MM-YYYY',
            minDate: new Date()
        });
        $(".filter").bootstrapMaterialDatePicker({
            weekStart: 0,
            time: false,
            format: 'DD-MM-YYYY',

        });

        tinymce.init({

            selector: '.editor',
            plugins: 'advlist autolink lists link image charmap print preview hr anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking save table directionality emoticons template paste textpattern imagetools codesample toc help fullpage',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',

        });



        function deleteMsg(url) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then(function (result) {
                if (result.value) {
                    location.href = url;
                }
            })
        }

        function statusMsg(url) {
            swal.fire({
                title: 'Are you sure?',
                text: "Don't Worry ! It Can be Revert",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Change the Status!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then(function (result) {
                if (result.value) {
                    location.href = url;
                }
            })
        }

    </script>


    {{-- cusotm js --}}
    <script>
        {!! (setting('dashboard_script', 1) ?? '') !!}

    </script>

    @yield('js')

</body>

</html>
