@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"> Settings</li>
                    </ol>
                </div>
                <h4 class="page-title">Settings</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form method="post" class="settings-submit params-card" autocomplete="off"
                        action="{{ route('white-label.save') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Whitelabel domain</label>
                                    <input type="text" class="form-control" name="whitelabel_domain"
                                        value="{{ setting('whitelabel_domain', auth()->user()->id) }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <strong class="font-weight-bold text-danger">Note : </strong>
                                <p class="font-weight-bold text-danger pl-2 ">
                                    @php
                                    $ip='70.32.23.103';
                                    @endphp
                                    First add a A record of any subdomain pointed
                                    to server ip {{ $ip }}
                                    <br> <span class="text-dark"> Example payment.mydomain.com A
                                        {{ $ip }} </span>
                                </p>
                                <p class="font-weight-bold text-danger pl-2 d-none">
                                   First add  custom domain nameserver  dns1.namecheaphosting.com and dns2.namecheaphosting.com, make sure parent domain not a subdomain
                                </p>

                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="ti-save"></i> Add Whitelabel
                                    or Re-SSL Check</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="{{ asset('plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/pages/jquery.clipboard.init.js') }}"></script>
    <script src="{{ asset('assets/js/iframe-resizer.js') }}"></script>
@endsection
