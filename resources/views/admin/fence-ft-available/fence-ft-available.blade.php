@extends('layouts.app')

@section('title', 'Fence Ft-Availables')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Fence Ft-Availables</li>
                    </ol>
                </div>
                <h4 class="page-title">Fence Ft-Availables</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!-- end page title end breadcrumb -->

    <div class="row">
        <div class="col-md-12 text-right">
            <a href="{{ route('fence-ft.list') }}" class="btn btn-gradient-primary px-4 mt-0 mb-3"><i class="mdi mdi-plus-circle-outline mr-2"></i>Add New</a>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>Added By</th>
                                <th>Fence Name</th>
                                <th>FtAvailables</th>
                                <th>Added About</th>
                            </tr><!--end tr-->
                            </thead>
                            <tbody>
                                @foreach ($data as $i=>$dat)
                                <tr>
                                    <td>
                                        {{ $dat->fence->user->name }}
                                    </td>
                                    <td>
                                        {{ $dat->fence->fence_name }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            @foreach(App\Models\FenceFtAvailable::with('ft_available')->where('fence_id',$dat->fence->id)->get() as $fts)
                                                <span>{{ $fts->ft_available->ft_available_name }} ,  </span>
                                            @endforeach
                                        </div>
                                   </td>
                                   <td>
                                    {{ $dat->fence->created_at->diffForHumans() }}
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
