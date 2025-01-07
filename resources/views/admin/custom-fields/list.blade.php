@extends('layouts.app')

@section('title', 'Custom Fields')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Custom Fields</li>
                    </ol>
                </div>
                <h4 class="page-title">Custom Fields</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!-- end page title end breadcrumb -->

    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ route('custom.add') }}" class="btn btn-gradient-primary px-4 mt-0 mb-3"><i class="mdi mdi-plus-circle-outline mr-2"></i>Add New</a>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>Custom Field Name</th>
                                <th class="text-right">Action</th>
                            </tr><!--end tr-->
                            </thead>
                            <tbody>

                                @foreach ($custom_fields as $index=>$data)
                                {{-- {{ dd($data) }} --}}
                                <tr>
                                    <td>{{ $data->custom_field_name }}</td>
                                    <td>
                                        <a href="{{ route('custom.edit',$data->id) }}" class="btn btn-sm btn-primary">edit</a>
                                        <a  href="{{ route('custom.delete',$data->id) }}" class="btn btn-sm btn-danger" onclick="event.preventDefault(); deleteMsg('{{ route('custom.delete',$data->id) }}')">Delete</a>
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
