@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Products</li>
                    </ol>
                </div>
                <h4 class="page-title">Products</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!-- end page title end breadcrumb -->

    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ route('product.add') }}" class="btn btn-gradient-info px-4 mt-0 mb-3"><i
                    class="mdi mdi-plus-circle-outline mr-2"></i>Add New</a>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="thead-light">
                                <tr>

                                    <th>Name</th>
                                    <th>Product Id</th>
                                    <th>Snapshot Id</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                <!--end tr-->
                            </thead>
                            <tbody>

                                @foreach ($products as $index => $data)
                                    {{-- {{ dd($data) }} --}}
                                    <tr>
                                        <td>{{ $data->name ?? 'N/A' }}</td>
                                        <td>{{ $data->product_id ?? 'N/A' }}</td>
                                        <td>{{ $data->snapshot_id ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('product.edit', $data->id) }}"
                                                class="btn btn-sm btn-primary">edit</a>
                                            <a href="{{ route('product.manage', $data->id) }}"
                                                class="btn btn-sm btn-primary">permissions</a>
                                            <a href="{{ route('product.delete', $data->id) }}" class="btn btn-sm btn-danger"
                                                onclick="event.preventDefault(); deleteMsg('{{ route('product.delete', $data->id) }}')">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach

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
        let table = $('#datatable').DataTable();
    </script>
@endsection
