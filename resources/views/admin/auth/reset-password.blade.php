<!DOCTYPE html>
<html lang="en">
@php
    $settings = getLoginCss('', 'all');
    
@endphp

<head>
    <meta charset="utf-8" />
    <title>Reset - {{ setting('software_name', 1) ?? 'Instant Fence Quote' }}</title>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <style>
        {!! setting('login_css', 1) ?? '' !!}
    </style>
</head>

<body class="account-body accountbg">

    <!-- Log In page -->
    <div class="container">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="row">
                    <div class="col-lg-5 mx-auto">
                        <div class="card">
                            <div class="card-body p-0 auth-header-box">
                                <div class="text-center p-3">
                                    <a href="index-2.html" class="logo logo-admin">
                                        <img src="{{ asset(setting('software_logo', 1)) ?? asset('assets/images/logo-sm.png') }}" height="50"
                                            alt="logo" class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white font-18">Reset Password </h4>
                                    <p class="text-muted  mb-0">Please enter your new password and confirm it below!
                                    </p>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <form class="my-4" action="{{ route('password.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                    <div class="form-group mb-3">
                                        <label class="form-label" for="username">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror "
                                            id="userEmail" name="email" placeholder="Enter Email Address"
                                            value="{{ old('email', request()->email) }}" readonly>

                                        @error('email')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror " id="password"
                                            name="password" placeholder="Enter password">

                                        @error('password')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label class="form-label" for="confirm_password">Confirm Password</label>
                                        <input type="password"
                                            class="form-control @error('confirm_password') is-invalid @enderror "
                                            id="confirm_password" name="password_confirmation"
                                            placeholder="Confirm password">

                                        @error('confirm_password')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-0 row">
                                        <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Reset <i
                                                    class="fas fa-sign-in-alt ms-1"></i></button>
                                        </div>
                                        <!--end col-->
                                    </div>
                                    <!--end form-group-->
                                </form>
                                <!--end form-->
                                <div class="text-center text-muted">
                                    <p class="mb-1">Remember It ? <a href="{{route('login')}}"
                                            class="text-primary ms-2">Sign in here</a></p>
                                </div>
                            </div>
                            <!--end card-body-->
                            <div class="card-body bg-light-alt text-center">
                                © {{ date('Y') }}
                                {{ setting('software_name', 1) ?? 'Instant Fence Quote' }}
                            </div>
                        </div>
                        <!--end card-->
                    </div>
                    <!--end col-->
                </div>
                <!--end row-->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <script>
        {!! setting('login_script', 1) ?? '' !!}
    </script>

    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}", {
                timeOut: 10000
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            toastr.error("{{ session('error') }}", {
                timeOut: 10000
            });
        </script>
    @endif

</body>

</html>
