@extends('layouts.app')

@section('title', 'Edit Custom fields')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('custom.list') }}">Custom Fields</a></li>
                        <li class="breadcrumb-item active">Edit Custom Field</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Custom Field</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!-- end page title end breadcrumb -->

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('custom.save', $data->id) }}" method="POST" class="card-box">
                        @csrf
                           <div class="form-group row">
                            <div class="col-md-12">
                                <label for="field_name">Field Name *</label>
                                <input type="text" placeholder="Field Name"
                                    class="form-control @error('field_name') is-invalid @enderror" name="field_name"
                                    value="{{ old('field_name',$data->custom_field_name) }}" id="field_name" autocomplete="off">
                                @error('field_name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <a href="{{ route('custom.list') }}" class="btn btn-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
