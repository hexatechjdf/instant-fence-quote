@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    {{-- breadcrumb --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
                <h4 class="page-title">Dashboard</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
    <!-- end page title end breadcrumb -->
<div class="row">
    @if(auth()->user()->role==1)
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 align-self-center">
                            <div class="icon-info">
                                <i data-feather="users" class="align-self-center icon-lg icon-dual-warning"></i>
                            </div>
                        </div>
                        <div class="col-8 align-self-center text-right">
                            <div class="ml-2">
                                <p class="mb-1 text-muted"> Total<br/> Users</p>
                                <h3 class="mt-0 mb-1 font-weight-semibold">{{totalUsers()}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
    @endif

        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 align-self-center">
                            <div class="icon-info">
                                <i data-feather="file-text" class="align-self-center icon-lg icon-dual-estimates"></i>
                            </div>
                        </div>
                        <div class="col-8 align-self-center text-right">
                            <div class="ml-2">
                                <p class="mb-1 text-muted">Total<br/> Estimates Done</p>
                                <h3 class="mt-0 mb-1 font-weight-semibold">{{ $estimates_count }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 align-self-center">
                            <div class="icon-info">
                            <i data-feather="check-circle" class="align-self-center icon-lg icon-dual-success"></i>
                            </div>
                        </div>
                        <div class="col-8 align-self-center text-right">
                            <div class="ml-2">
                                <p class="mb-1 text-muted">Total<br/> Successful Estimates </p>
                                <h3 class="mt-0 mb-1 font-weight-semibold">{{ $successful_estimates_count }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
    @if(auth()->user()->role==1)
        <div class="col-lg-3" hidden>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 align-self-center">
                            <div class="icon-info">
                                <i data-feather="smile" class="align-self-center icon-lg icon-dual-warning"></i>
                            </div>
                        </div>
                        <div class="col-8 align-self-center text-right">
                            <div class="ml-2">
                                <p class="mb-1 text-muted"> Custom Fields</p>
                                <h3 class="mt-0 mb-1 font-weight-semibold">{{totalCustomFields()}}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>

    @endif
    <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-4 align-self-center">
                            <div class="icon-info">
                            <i data-feather="alert-circle" class="align-self-center icon-lg icon-dual-warning"></i>
                            </div>
                        </div>
                        <div class="col-8 align-self-center text-right">
                            <div class="ml-2">
                                <p class="mb-1 text-muted"> Total <br>Incomplete Estimates  </p>
                                <h3 class="mt-0 mb-1 font-weight-semibold">{{ $failed_estimates_count }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
</div>

{{-- recent Users --}}
@if(auth()->user()->role==1)
    <div class="row">
        <div class="col-md-12 text-left">
            <h4 class="page-title">Recent User</h4>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="thead-light">
                            <tr>
                                <th> Name</th>
                                <th> Email</th>

                                <th>Location</th>
                                <th>Registered At</th>
                            </tr><!--end tr-->
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>

                                        <td>{{ $user->location }}</td>
                                        <td>{{ $user->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row">
      <div class="col-md-12">
            <h4 class="page-title">Recent Estimates</h4>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th> Fence Image </th>
                                    <th> Estimated About </th>
                                    <th> Contact Name </th>
                                    <th> Contact Email </th>
                                    <th> Contact Phone </th>
                                    <th> Address </th>
                                    <th> Total Feet </th>
                                    <th> Status </th>
                                    <th> Fence Type </th>
                                    <th> Fence Style </th>
                                    <th> Desired Height </th>
                                    <th> Estimated Range </th>
                                    <th> No. Of D/G </th>
                                    <th> No. Of S/G </th>
                                    <th> Single Price </th>
                                    <th> Double Price </th>
                                    <th> Fence Estimation </th>
                                    <th> Min Estimate </th>
                                    <th> Max Estimate </th>
                                    <th> Action </th>
                                </tr>
                                <!--end tr-->
                            </thead>
                            <tbody>
                                 @php $symb = setting('estimate_currency_symbol', auth()->user()->id) ?? '' @endphp
                                @foreach ($estimates as $estimate)
                                    <tr>
                                          <td class="text-center">{!! $estimate->main_image !!}</td>
                                        <td>{{ $estimate->created_at->diffForHumans() }}</td>
                                        <td>{{ $estimate->name ?? '' }}</td>
                                        <td>{{ $estimate->email  ?? '' }}</td>
                                        <td>{{ $estimate->phone  ?? '' }}</td>
                                        <td>{{ $estimate->address  ?? '' }}</td>
                                        <td>{{ $estimate->feet  ?? '' }} ft.</td>

                                        <td>
                                            <span class="badge badge-{{ $estimate->is_completed ? 'success' : 'danger' }}">
                                                {{ $estimate->is_completed ? 'Completed' : 'Incompleted' }}
                                        </td>
                                        <td>{{ $estimate->category_label  ?? ''}}</td>
                                        <td>{{ $estimate->fence_label  ?? ''}}</td>
                                        <td>{{ $estimate->height_label  ?? ''}}</td>
                                        <td>{{ $estimate->height_label  ?? ''}}</td>
                                        <td>{{ $estimate->single_gates  ?? ''}}</td>
                                        <td>{{ $estimate->double_gates  ?? ''}}</td>
                                        <td>{{ $estimate->single_price ?? '' }}
                                            {{$symb }} </td>
                                        <td>{{ $estimate->double_price }}
                                            {{ $symb }} </td>
                                        <td>{{ $estimate->fence_estimation }}
                                            {{ $symb }}</td>
                                        <td>{{ $estimate->min_estimate }}
                                            {{ $symb }}</td>
                                        <td>{{ $estimate->max_estimate }}
                                            {{ $symb }}</td>

                                        <td>
                                            <a href="{{ route('estimate.resend', $estimate->id) }}"
                                                class="btn btn-sm btn-primary survey_resend">Resend</a>
                                            <a href="{{ route('estimate.delete', $estimate->id) }}"
                                                class="btn btn-sm btn-danger survey_delete">Delete</a>
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
