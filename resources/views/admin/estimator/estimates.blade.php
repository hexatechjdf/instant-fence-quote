@extends('layouts.app')
@section('title', 'Estimates')
@section('content')
    {{-- breadcrumb --}}
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item ">Dashboard</li>
                        <li class="breadcrumb-item active">Estimates</li>
                    </ol>
                </div>
                <h4 class="page-title">Estimates</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable" class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th> Fence Image </th>
                                    <th> Date </th>
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

                                                <!-- {{ route('estimate.delete', $estimate->id) }} -->
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
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.survey_delete');









            deleteButtons.forEach(function(button) {
                button.addEventListener('click', async function(e) {
                    e.preventDefault();
                    const url = e.currentTarget.getAttribute('href');

                    const response = await fetch(url, {
                        method: 'GET' 
                    });

                    const data = await response.json();

                    console.log(data);


                    if (data.status === 'success') {
                        console.log(data.message);
                        toastr.success(data.message);

                        // Refresh the page
                        location.reload();
                    } else {
                        toastr.error(`Error has an occured. Estimate cannot be deleted inside Rogue Leads CRM. Please Contact Support.`);
                        console.error(data.message);
                    }
                });
            });
        });

    
        $(document).ready(function() {
            $('#datatable').DataTable({
                "ordering": false // Disable DataTables ordering
            });
        });


        $('body').on('click', '.survey_resend', function(e) {
            e.preventDefault();
            $('.loading').show();
            var url = $(this).attr('href');
            //send ajax request
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    if (data.status == 'success') {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(data) {
                    toastr.error(data.message);
                },
                complete: function() {
                    $('.loading').hide();
                }
            });
        })


        $('body').on('click', '.survey_delete', function(e) {
            e.preventDefault();
            $('.loading').show();
            var url = $(this).attr('href');
            //send ajax request
            var mhit = $(this);
            $.ajax({
                url: url,
                type: 'GET',

                success: function(data) {
                    // if (data.status == 'success') {
                    //     toastr.success(data.message);
                    //     mhit.closest('tr').remove();
                    // } else {
                    //     toastr.error(data.message);
                    // }
                },
                error: function(data) {
                    // toastr.error(`Error has an occured. Estimate cannot be deleted inside Rogue Leads CRM. Please Contact Support.`);
                },
                complete: function() {
                    $('.loading').hide();
                }
            });
        })
    </script>
@endsection
