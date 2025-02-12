<!DOCTYPE html>
<html lang="en">
@php
    $settings = getLoginCss('', 'all');
@endphp

<head>
    <meta charset="utf-8" />
    <title>Register-{{ setting('software_name', 1) ?? 'Instant Fence Quote' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/metisMenu.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        {!! setting('login_css', 1) ?? '' !!}
    </style>
</head>

<body class="account-body accountbg">
    <!-- Log In page -->
    <div class="container">
        <div class="row vh-100">
            <div class="col-12 align-self-center">
                <div class="auth-page">
                    <div class="card auth-card shadow-lg">
                        <div class="card-body">
                            <div class="px-3">
                                <div class="auth-logo-box mt-5">
                                    <a href="{{ route('dashboard') }}" class="logo logo-admin"><img
                                            src="{{ asset(setting('software_logo', 1)) ?? asset('assets/images/logo-sm.png') }}"
                                            height="55" alt="logo" class="auth-logo"></a>
                                </div>
                                <!--end auth-logo-box-->
                                <div class="text-center auth-logo-text pt-4">
                                    <h4 class="mt-0 mb-3 mt-5">Register Account</h4>
                                    <p class="text-muted">Note: Upon completing your account registration, it will
                                        undergo an administrative review and remain pending for approval. You can expect
                                        the review process to be completed within the next 24 hours.</p>
                                </div>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!--end auth-logo-text-->
                                <form class="form-horizontal auth-form my-4" action="{{ route('register') }}"
                                    method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <div class="input-group mb-2">
                                            <span class="auth-form-icon">
                                                <i class="dripicons-user"></i>
                                            </span>
                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="Enter Name" required>
                                        </div>
                                    </div>
                                    <!--end form-group-->
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <div class="input-group mb-2">
                                            <span class="auth-form-icon">
                                                <i class="dripicons-mail"></i>
                                            </span>
                                            <input type="email" class="form-control" name="email" id="email"
                                                placeholder="Enter Email" required>
                                        </div>
                                    </div>
                                    <!--end form-group-->
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <div class="input-group mb-2">
                                            <span class="auth-form-icon" style="cursor: pointer" onclick="togglePass()">
                                                <i class="fa fa-eye text-blue"></i>
                                            </span>
                                            <input type="password" class="form-control" name="password" id="password"
                                                placeholder="Enter password" required>
                                        </div>
                                    </div>
                                    <!--end form-group-->
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm Password</label>
                                        <div class="input-group mb-2">
                                            <span class="auth-form-icon" style="cursor: pointer"
                                                onclick="toggleCPass()">
                                                <i class="fa fa-eye text-blue"></i>
                                            </span>
                                            <input type="password" class="form-control" name="confirm_password"
                                                id="confirm_password" placeholder="Enter Confirm Password" required>
                                        </div>
                                    </div>
                                    <!--end form-group-->
                                    <div class="form-group row mt-3">
                                        <div class="col-sm-12">
                                            <div class="custom-control custom-switch switch-success">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="customSwitchSuccess">
                                                <label class="custom-control-label text-muted"
                                                    for="customSwitchSuccess">By registering you agree to the Our <a
                                                        href="{{ route('extra-page.term-of-service') }}"
                                                        class="text-primary">Terms of Use</a></label>
                                            </div>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end form-group-->
                                    <div class="form-group mb-0 row">
                                        <div class="col-12 mt-2">
                                            <button type="submit"
                                                class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light">Register
                                                <i class="fas fa-sign-in-alt ml-1"></i></button>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end form-group-->
                                </form>
                                <!--end form-->
                            </div>
                            <!--end /div-->
                            <div class="m-3 text-center text-muted">
                                <p class="">Already have an account ? <a href="{{ route('login') }}"
                                        class="text-primary ml-2">Log in</a></p>
                            </div>
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end auth-card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
    </div>
    <!--end container-->
    <!-- End Log In page -->

    <!-- jQuery  -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/metismenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/waves.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script>
        function togglePass() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }

        function toggleCPass() {
            var x = document.getElementById("confirm_password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>

    <script>
        {!! setting('login_script', 1) ?? '' !!}
    </script>
</body>

</html>
