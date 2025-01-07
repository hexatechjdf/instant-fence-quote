@extends('layouts.app')

@section('title', 'Custom Pages')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Custom Pages</li>
                    </ol>
                </div>
                <h4 class="page-title">Custom Pages</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!-- end page title end breadcrumb -->

    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ route('custom-pages.add') }}" class="btn btn-gradient-primary px-4 mt-0 mb-3"><i
                    class="mdi mdi-plus-circle-outline mr-2"></i>Add New</a>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>Page Created For</th>
                                    <th>Page Name</th>
                                    <th>Page Description</th>
                                    <th class="text-right">Action</th>
                                </tr>
                                <!--end tr-->
                            </thead>
                            <tbody>
                                @foreach ($pages as $page)
                                    <tr>
                                        @if ($page->show_to_all == 0)
                                            <td>{{ $page->company->name ?? '' }}</td>
                                        @else
                                            <td>
                                                <span class="badge badge-pill badge-primary">All Users</span>
                                            </td>
                                        @endif
                                        <td>{{ $page->name }}</td>
                                        @if ($page->type == 'page')
                                            <td>{{ Illuminate\Support\Str::limit($page->description, 150) }}</td>
                                        @else
                                            <td> {{ $page->link ?? '' }}</td>
                                        @endif
                                        <td>
                                            <a href="{{ route('custom-pages.visit', $page->id) }}"
                                                class="btn btn-gradient-success "><i class="mdi mdi-eye-outline "></i></a>
                                            <a href="{{ route('custom-pages.edit', $page->id) }}"
                                                class="btn btn-gradient-primary "><i class="mdi mdi-pencil-outline"></i></a>
                                            <a href="{{ route('custom-pages.delete', $page->id) }}"
                                                class="btn btn-gradient-danger "><i class="mdi mdi-delete-outline"
                                                    onclick="event.preventDefault(); deleteMsg('{{ route('custom-pages.delete', $page->id) }}')"></i></a>
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

@endsection
