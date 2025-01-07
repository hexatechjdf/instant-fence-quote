@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('product.list') }}">Products</a></li>
                        <li class="breadcrumb-item active">Edit Product</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Product </h4>
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
                    <form action="{{ route('product.save',$product->id) }}" method="POST" class="card-box">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="name">Product Name</label>
                                <input type="text" placeholder="Product Name"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ $product->name }}" id="name" autocomplete="off">
                                @error('name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="product_id">Internal Product ID</label>
                                <input type="text" placeholder="Internal Product ID"
                                    class="form-control @error('product_id') is-invalid @enderror" name="product_id"
                                    value="{{ $product->product_id }}" id="product_id" autocomplete="off">
                                @error('product_id')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="snapshot_id">Snapshot ID</label>
                                <input type="text" placeholder="Internal Product ID"
                                    class="form-control @error('snapshot_id') is-invalid @enderror" name="snapshot_id"
                                    value="{{ $product->snapshot_id }}" id="snapshot_id" autocomplete="off">
                                @error('snapshot_id')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <a href="{{ route('product.list') }}"
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
