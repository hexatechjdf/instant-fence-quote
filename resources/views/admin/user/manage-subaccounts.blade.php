@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Manage Subaccounts</li>
                    </ol>
                </div>
                <h4 class="page-title">Manage Subaccounts</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card p-3">


                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="col-md-2">
                        <button class="btn btn-primary" onclick="window.history.back()">Back to List</button>
                    </div>

                    <div class="col-md-4 d-flex justify-content-end">

                        <div class="col-md-3 m-2">
                            <button id="save-user-details-btn" class="btn btn-info w-100">Save User Details</button>
                        </div>
                        <div class="col-md-3 m-2">
                            <button id="connect-locations-btn" class="btn btn-success w-100">Connect Locations</button>
                        </div>
                        <div class="col-md-6 m-2">
                            <input type="text" id="search" class="form-control" placeholder="Search by name, email, or location">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Old Location</th>
                                <th>Connect Location</th>
                                <th>Already Matched</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr data-user-id="{{ $user->id }}">
                                    <td>{{ $user->name ?? 'No name' }}</td>
                                    <td>{{ $user->email ?? 'No Email' }}</td>
                                    <td>{{ $user->location ?? 'No Location' }}</td>
                                    <td>
                                        @if ($allLocations)
                                            @if (in_array($user->location, $crmlocationID))
                                                <select class="form-select location-select" style="width: 100%;">
                                                    <option value="">Open this select menu</option>
                                                    @foreach ($allLocations as $loc)
                                                        <option @if ($loc->id == $user->location) selected @endif
                                                            value="{{ $loc->id }}">{{ $loc->name }} --
                                                            {{ $loc->id }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" name="location-manual"
                                                    class="form-control location-input" placeholder="Enter Location" value="{{ $user->location }}">
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if (in_array($user->location, $crmlocationID))
                                            Yes
                                        @else
                                            No
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            $('.location-select').select2({
                placeholder: "Select Location",
                allowClear: true,
            });

            function collectUserData() {
                let userData = [];

                document.querySelectorAll('tbody tr').forEach(row => {
                    let userId = row.getAttribute('data-user-id');
                    let selectElement = row.querySelector('.location-select');
                    let inputElement = row.querySelector('.location-input');

                    let locationId = null;
                    let isManual = false;

                    if (selectElement && selectElement.value) {
                        locationId = selectElement.value;
                        isManual = false;
                    } else if (inputElement && inputElement.value.trim()) {
                        locationId = inputElement.value.trim();
                        isManual = true;
                    }

                    if (userId && locationId) {
                        userData.push({
                            userId: userId,
                            locationId: locationId,
                            is_manual: isManual
                        });
                    }
                });

                return userData;
            }

            function sendUserData(endpoint, button) {
                let userData = collectUserData();

                if (userData.length === 0) {
                    alert('No users with selected locations.');
                    return;
                }

                button.disabled = true; // Disable button during request

                $.ajax({
                    url: endpoint,
                    type: 'POST',
                    data: JSON.stringify(userData),
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        toastr.error('Something went wrong');
                    },
                    complete: function() {
                        button.disabled = false; // Re-enable button after request
                    }
                });
            }


            // Event listeners for separate buttons
            document.getElementById('save-user-details-btn').addEventListener('click', function() {
                sendUserData('{{ route('user.save-user-detail') }}', this);
            });

            document.getElementById('connect-locations-btn').addEventListener('click', function() {
                sendUserData('{{ route('user.connect-locations') }}', this);
            });

            // Search functionality
            document.getElementById('search').addEventListener('input', function(e) {
                const query = e.target.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(row => {
                    const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const location = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                    row.style.display = (name.includes(query) || email.includes(query) || location
                        .includes(query)) ? '' : 'none';
                });
            });
        });
    </script>
@endsection
