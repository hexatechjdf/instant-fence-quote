@extends('layouts.app')

@section('title', 'Add User')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.list') }}">Users</a></li>
                        <li class="breadcrumb-item active">Add New</li>
                    </ol>
                </div>
                <h4 class="page-title">Add New User</h4>
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
                    <form action="{{ route('user.save') }}" method="POST" class="card-box">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="first_name">Name *</label>
                                <input type="text" placeholder="Name"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" id="name" autocomplete="off">
                                @error('name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="email">Email *</label>
                                <input type="text" placeholder="Email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" id="email" autocomplete="off">
                                @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12 mt-3 mb-3">
                                <!-- Toggle Switch -->
                                <input type="checkbox" id="separate_location" class="form-check-input" name="separate_location">
                                <label for="separate_location" class="form-check-label ml-2">Separate location? <strong class="ml-3"> ( Note: </strong><small class="text-danger"> Need to be Toggle ON if the user have a separate location </small>)</label>
                            </div>
                        </div>
                        <div class="form-group row" hidden>
                            <div class="col-md-12">
                                <label for="email"> Rogue CRM API Key    <strong class="ml-3"> ( Note: </strong><small class="text-danger"> Insert only If You want to send the estimate informations to the Rogue CRM  </small>)</label>
                                <textarea placeholder="Api Key" class="form-control @error('api_key') is-invalid @enderror" name="api_key"></textarea>
                                @error('api_key')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="survey_id">Unique Estimator Survey Id *  <strong class="ml-3"> ( Note: </strong><small class="text-danger"> It will be the unique identifier for User's estimator survey </small>)</label>
                                <textarea placeholder="Unique Estimator Survey" class="form-control @error('survey_id') is-invalid @enderror" name="survey_id"
                                    value="{{ old('survey_id') }}" id="survey_id"></textarea>
                                @error('survey_id')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="password">Password *</label>
                                <input type="password" placeholder="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    value="{{ old('password') }}" id="password" autocomplete="off">
                                @error('password')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="{{ route('user.list') }}"
                                class="btn btn-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Cancel</a>
                            <button type="submit"
                                class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
