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

    <p id="errorMesssage"></p>
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
                                <th>User Type ?</th>
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
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input separate-location-toggle" name="separate_location" id="separate_location_{{ $user->id }}"
                                                data-user-id="{{ $user->id }}"
                                                @if (!in_array($user->location, $crmlocationID)) checked @endif>
                                            <label class="form-check-label" for="separate_location_{{ $user->id }}">Separate Location ?</label>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($allLocations)
                                            <div class="location-select-wrapper">
                                                <input type="text" name="location-manual" class="form-control location-input" id="location_input_{{ $user->id }}" placeholder="Enter Location" value="{{ $user->location }}" style="display: none;">
                                                <select class="form-select location-select" id="location_select_{{ $user->id }}" style="width: 100%;">
                                                    <option value="">Open this select menu</option>
                                                    @foreach ($allLocations as $loc)
                                                        <option @if ($loc->id == $user->location) selected @endif value="{{ $loc->id }}">{{ $loc->name }} -- {{ $loc->id }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
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
    $(document).ready(function() {
        // Function to toggle between input and select based on checkbox state
        function toggleLocationInput(checkbox) {
            const userId = $(checkbox).data('user-id');
            const inputElement = $('#location_input_' + userId);
            const selectElement = $('#location_select_' + userId);

            if (checkbox.checked) {
                // If checkbox is checked, show the input field
                inputElement.show();
                selectElement.hide();
            } else {
                // If checkbox is unchecked, show the select element
                inputElement.hide();
                selectElement.show();
            }
        }

        // On page load, toggle visibility based on the initial state of the checkbox
        $('.separate-location-toggle').each(function() {
            toggleLocationInput(this); // Ensure initial state is correct

            // Change event to toggle visibility when checkbox state changes
            $(this).change(function() {
                toggleLocationInput(this);
            });
        });

        // Function to collect user data
        function collectUserData() {
            let userData = [];

            $('tbody tr').each(function() {
                let userId = $(this).data('user-id');
                let checkbox = $(this).find('.separate-location-toggle');
                let selectElement = $(this).find('.location-select');
                let inputElement = $(this).find('.location-input');
                let locationId = null;
                let isManual = checkbox.prop('checked');
                if (isManual && inputElement.val().trim()) {
                    locationId = inputElement.val().trim();
                } else if (!isManual && selectElement.val()) {
                    locationId = selectElement.val();
                }
                if (userId) {
                    userData.push({
                        userId: userId,
                        locationId: locationId,
                        is_manual: isManual
                    });
                }
            });
            return userData;
        }
        // Function to send user data to the backend
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
                    let response = xhr.responseJSON;

                    if (response && response.status === "error" && Array.isArray(response.message)) {
                        let errorMessages = response.message.join("<br>");
                        $("#errorMesssage").html(errorMessages); // Update error message in <p>
                    } else {
                        toastr.error(error);
                    }
                },
                complete: function() {
                    button.disabled = false; // Re-enable button after request
                }
            });
        }

        // Event listeners for buttons
        $('#save-user-details-btn').click(function() {
            sendUserData('{{ route('user.save-user-detail') }}', this);
        });

        $('#connect-locations-btn').click(function() {
            sendUserData('{{ route('user.connect-locations') }}', this);
        });

        // Search functionality
        $('#search').on('input', function(e) {
            const query = e.target.value.toLowerCase();
            $('tbody tr').each(function() {
                const name = $(this).find('td:nth-child(1)').text().toLowerCase();
                const email = $(this).find('td:nth-child(2)').text().toLowerCase();
                const location = $(this).find('td:nth-child(3)').text().toLowerCase();

                $(this).toggle(name.includes(query) || email.includes(query) || location.includes(query));
            });
        });
    });
    </script>
@endsection
