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
                        action="{{ route('setting.save') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Dashboard CSS</label>
                                    <textarea class="form-control " rows="5" name="dashboard_css">{{ setting('dashboard_css', auth()->user()->id) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="ti-save"></i> Save CSS </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form method="post" class="settings-submit params-card" autocomplete="off"
                        action="{{ route('setting.save') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Dashboard Script</label>
                                    <textarea class="form-control " rows="5" name="dashboard_script">{{ setting('dashboard_script', auth()->user()->id) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="ti-save"></i> Save Script</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form method="post" class="settings-submit params-card" autocomplete="off"
                        action="{{ route('setting.save') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Login Page CSS</label>
                                    <textarea class="form-control " rows="5" name="login_css">{{ setting('login_css', auth()->user()->id) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="ti-save"></i> Save CSS </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form method="post" class="settings-submit params-card" autocomplete="off"
                        action="{{ route('setting.save') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Login Script</label>
                                    <textarea class="form-control " rows="5" name="login_script">{{ setting('login_script', auth()->user()->id) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="ti-save"></i> Save Script</button>
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
