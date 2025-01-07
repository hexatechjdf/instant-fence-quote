<!DOCTYPE html>
<html lang="en">
@php
    $settings = getLoginCss('', 'all');
    // you can use the following to show the logins css for wide label domains
    // get_value($settings, 'software_name')
@endphp

<head>
    <meta charset="utf-8" />
    <title>Login - {{ setting('software_name', 1) ?? 'Intant Fence Quote' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
        <div class="row vh-100 ">
            <div class="col-12 align-self-center">
                <div class="auth-page">
                    <div class="card auth-card shadow-lg">
                        <div class="card-body">
                            <div class="px-3">
                                <div class="auth-logo-box">
                                    <a href="{{ route('dashboard') }}" class="logo logo-admin">
                                        <img src="{{ asset(setting('software_logo', 1)) ?? asset('assets/images/logo-sm.png') }}"
                                            height="55" alt="logo" class="auth-logo"
                                            onerror="this.src='{{ asset('assets/images/logo-sm.png') }}'">

                                    </a>
                                </div>
                                <!--end auth-logo-box-->

                                <div class="text-center auth-logo-text">
                                    {{-- get_value($settings, 'company_title', 'Welcome') --}}
                                    <h4 class="mt-0 mb-3 mt-5">
                                        {{ setting('software_name', 1) ?? 'Intant Fence Quote' }}
                                    </h4>
                                    <p class="text-muted mb-0">Sign In</p>
                                </div>
                                <!--end auth-logo-text-->


                                <form class="form-horizontal auth-form my-4" method="POST"
                                    action="{{ route('login') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <div class="input-group mb-3">
                                            <span class="auth-form-icon">
                                                <i class="dripicons-user"></i>
                                            </span>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                id="email" placeholder="Enter email" value="{{ old('email') }}"
                                                autocomplete="off">
                                            @error('email')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end form-group-->

                                    <div class="form-group">
                                        <label for="userpassword">Password</label>
                                        <div class="input-group mb-3">
                                            <span class="auth-form-icon">
                                                <i class="dripicons-lock"></i>
                                            </span>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" id="password" placeholder="Enter password"
                                                autocomplete="off">
                                            @error('password')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--end form-group-->

                                    <div class="form-group row mt-4">
                                        <div class="col-sm-6">
                                            <div class="custom-control custom-switch switch-success">
                                                <input type="checkbox" class="custom-control-input" id="remember_me"
                                                    name="remember">
                                                <label class="custom-control-label text-muted"
                                                    for="remember_me">Remember me</label>
                                            </div>
                                        </div>
                                        <!--end col-->
                                        <div class="col-sm-6 text-right" >
                                            <a href="{{ route('password.request') }}" class="text-muted font-13"><i
                                                    class="dripicons-lock"></i>
                                                Forgot password?</a>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end form-group-->

                                    <div class="form-group mb-0 row">
                                        <div class="col-12 mt-2">
                                            <button
                                                class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light"
                                                type="submit">Log In <i class="fas fa-sign-in-alt ml-1"></i></button>
                                        </div>
                                        <!--end col-->
                                    </div>

                                    <div class="m-3 text-center text-muted">
                                        <p class="">Don't have an account ? <a href="{{ route('register') }}"
                                                class="text-primary ml-2">Register Now!</a></p>
                                    </div>
                                    <!--end form-group-->
                                </form>
                                <!--end form-->
                            </div>
                            <!--end /div-->
                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div>
                <!--end auth-page-->
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
        {!! get_value($settings, 'login_script') !!}
    </script>

</body>

</html>
