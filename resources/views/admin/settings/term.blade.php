@extends('layouts.app')

@section('title', 'Terms & Conditions')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"> Terms & Conditions</li>
                    </ol>
                </div>
                <h4 class="page-title">Terms & Conditions</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!-- end page title end breadcrumb -->
    
    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ route('extra-page.term-of-service') }}" class="btn btn-gradient-primary px-4 mt-0 mb-3"  target="_blank"><i
                    class="mdi mdi-plus-circle-outline mr-2"></i>View Page</a>
        </div>
        <div class="col-md-12 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form method="post" class="settings-submit params-card" autocomplete="off"
                        action="{{ route('setting.save') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Terms and Conditions Content</label>
                                    <textarea class="form-control editor" rows="10" name="terms_and_conditions">{{ setting('terms_and_conditions', auth()->user()->id) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"><i class="ti-save"></i> Save Terms and Conditions  </button>
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
