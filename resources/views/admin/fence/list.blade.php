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
                        <li class="breadcrumb-item active">Fences</li>
                    </ol>
                </div>
                <h4 class="page-title">Fences</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!-- end page title end breadcrumb -->

    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ route('fence.add') }}" class="btn btn-gradient-primary px-4 mt-0 mb-3"><i class="mdi mdi-plus-circle-outline mr-2"></i>Add New</a>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Ft Available</th>
                                <th> Category Name</th>
                                <th>Prices</th>
                                <th>Is Active</th>
                                <th class="text-right">Action</th>
                            </tr><!--end tr-->
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        // Datatable
        let table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url" : "{{ route('fence.list') }}",
            },
            columns: [
                {data: 'fence_image', name: 'fence_image'},
                {data: 'fence_name', name: 'fence_name'},
                {data: 'ft_available', name: 'ft_available' , orderable:false , searchable:false},
                {data: 'category', name: 'category.name' },
                {data: 'prices', name: 'prices' },
                {data: 'status', name: 'status' , searchable:false},
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    class: 'text-right'
                },
            ]
        });
    </script>
@endsection
