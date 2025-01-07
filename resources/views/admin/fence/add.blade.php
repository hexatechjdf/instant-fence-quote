@extends('layouts.app')

@section('title', 'Fences')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('fence.list') }}">Fence</a></li>
                        <li class="breadcrumb-item active">Add New</li>
                    </ol>
                </div>
                <h4 class="page-title">Add New </h4>
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
                    <form action="{{ route('fence.save') }}" method="POST" class="card-box" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="">For Category *</label>
                                <select name="category_id"
                                    class="select2 form-control mb-3 custom-select select2-hidden-accessible @error('category_id') is-invalid @enderror"
                                    style="width: 100%; height:36px;" tabindex="-1" aria-hidden="true">
                                    <option selected disabled> Select </option>
                                    @foreach ($categories as $category)
                                        <option value={{ $category->id }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="image">Fence Style Image </label>
                                <input type="file" placeholder="insert the CRM media link here"
                                    class=" form-control dropify" name="fence_image">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="fence_name">Fence Name *</label>
                                <input type="text" placeholder="Fence Name"
                                    class="form-control @error('fence_name') is-invalid @enderror" name="fence_name"
                                    value="{{ old('fence_name') }}" id="fence_name" autocomplete="off">
                                @error('fence_name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        {{-- ft availables --}}
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="">Select Ft. Availables</label>
                                <select name="ft_available_id[]"
                                    class="select2 mb-3 select2-multiple select2-hidden-accessible @error('ft_available_id') is-invalid @enderror"
                                    style="width: 100%" multiple="" data-placeholder="Choose" tabindex="-1"
                                    aria-hidden="true">
                                    @foreach ($ft_availables as $ft_available)
                                        <option value="{{ $ft_available->id }}">{{ $ft_available->ft_available_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ft_available_id')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="{{ route('fence.list') }}"
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
