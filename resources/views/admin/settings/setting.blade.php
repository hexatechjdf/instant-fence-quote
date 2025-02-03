@extends('layouts.app')

@section('title', 'Settings')

@section('content')

    @php
        $authUser = auth()->user();
    @endphp
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active"> Settings</li>
                    </ol>
                </div>
                <h4 class="page-title">Settings</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!-- end page title end breadcrumb -->
    <div class="row">
        @if ($authUser->role == 1)
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0"> General Settings </h4>
                        <form action="{{ route('setting.save') }}" method="POST" id="general_profile"
                            enctype="multipart/form-data">
                            @csrf
                             <div class="row">
                                <div class=" text-center mx-auto">
                                    <div class="row">
                                        <div class="col-md-12 text-left">
                                            <div class="form-group">
                                                <label for="last_name "> Software Name </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                    </div>
                                                    <input type="text" name="software_name" class="form-control"
                                                        autocomplete="off" autofocus="autofocus"
                                                        value="{{ setting('software_name', $authUser->id) ?? 'Instant Fence Price' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 text-left">
                                            <div class="form-group">
                                                <label for="last_name "> Software Email </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                    </div>
                                                    <input type="text" name="software_email" class="form-control"
                                                        autocomplete="off" autofocus="autofocus"
                                                        value="{{ setting('software_email', $authUser->id) ?? 'roguebusinessmarketing@gmail.com' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12 text-left">
                                            <div class="form-group">
                                                <label for="last_name ">  Lead Management System URL </label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                                    </div>
                                                    <input type="text" name="lead_management_system" class="form-control" placeholder="https://example.com"
                                                        autocomplete="off" autofocus="autofocus"
                                                        value="{{ setting('lead_management_system', $authUser->id) ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 text-left">
                                        <div class="form-group">
                                            <label for="crm_client_id">CRM Client Key</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                </div>
                                                <input type="text" name="crm_client_id" class="form-control" placeholder="Enter CRM Client Id"
                                                    autocomplete="off" autofocus="autofocus"
                                                    value="{{ setting('crm_client_id', $authUser->id) ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 text-left">
                                        <div class="form-group">
                                            <label for="crm_client_secret">CRM Secret Key</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                </div>
                                                <input type="text" name="crm_client_secret" class="form-control" placeholder="Enter CRM Secret Id"
                                                    autocomplete="off" autofocus="autofocus"
                                                    value="{{ setting('crm_client_secret', $authUser->id) ?? '' }}">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12 text-left">
                                        <div class="form-group">
                                            <label for="sso_key">SSO Key</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                </div>
                                                <input type="text" name="sso_key" class="form-control" placeholder="Enter SSO Key "
                                                    autocomplete="off" autofocus="autofocus"
                                                    value="{{ setting('sso_key', $authUser->id) ?? '' }}">
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-12 text-left">
                                            <div class="form-group">
                                                <label for="email">Software Logo</label>
                                                <div class="input-group">
                                                    <input type="file" name="software_logo" class="form-control dropify"
                                                        autocomplete="off" autofocus="autofocus"
                                                        data-default-file="{{ asset(setting('software_logo', $authUser->id)) ?? asset('assets/images/logo-sm.png') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row text-left">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="email"> Welcome Email Note :</label>
                                                <div class="input-group">
                                                    <textarea name="welcome_email_note" class="form-control editor"
                                                        placeholder="please write about the company for new user welcome email" autocomplete="off" autofocus="autofocus"> {{ setting('welcome_email_note', $authUser->id) ?? '' }} </textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-12 text-right">
                                                <button type="submit" class="btn btn-gradient-warning px-4 mt-0 mb-0"> Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="row text-center">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <p class="font-weight-bold fs-4">
                                        <a class="btn btn-primary" href="{{ $connecturl }}">
                                           Connect With CRM Agency
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if ($authUser->role == 0)
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0"> Webhooks Setting </h4>
                        <form action="{{ route('setting.save') }}" method="POST" id="general_profile"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="last_name"> Webhook URL </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="clipboardInput"
                                                value="{{ setting('webhook_url', $authUser->id) }}"
                                                aria-label="webhook url" aria-describedby="button-addon2"
                                                name="webhook_url">
                                            <button class="btn btn-secondary " type="button" id="button-addon2"
                                                data-clipboard-action="copy" data-clipboard-target="#clipboardInput"><i
                                                    class="far fa-copy me-2"></i>Copy</button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-12 text-right">
                                                <button type="submit" class="btn btn-gradient-warning px-4 mt-0 mb-0"> Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mt-0"> Embed Estimator Url </h4>
                        <form action="{{ route('setting.save') }}" method="POST" id="general_profile"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="last_name"> Integrate Estimator </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                            </div>
                                            @php
                                                $location_id = $authUser->location;
                                            @endphp
                                            <textarea rows="3" class="form-control text-left" id="estimator_iframe" aria-label="estimator_iframe url"
                                                aria-describedby="button-addon3"><iframe src="{{ route('estimator.index', $location_id) }}" class="estimator" frameborder="0" style="border:none;width:100%;height: 100vh;"  id="{{ $location_id }}"></iframe><script src="{{ asset('assets/js/iframe-resizer.js') }}"></script></textarea>
                                            <button class="btn btn-secondary " type="button" id="button-addon3"
                                                data-clipboard-action="copy" data-clipboard-target="#estimator_iframe"><i
                                                    class="far fa-copy me-2"></i>Copy</button>
                                        </div>
                                    </div>
                                    {{-- copy the url --}}
                                    <div class="form-group">
                                        <label for="last_name"> Copy Estimator Link </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="estimator_link"
                                                value="{{ route('estimator.index', $location_id) }}"
                                                aria-label="estimator_link url" aria-describedby="button-addon4"
                                                name="estimator_link">
                                            <button class="btn btn-secondary " type="button" id="button-addon4" data-clipboard-action="copy" data-clipboard-target="#estimator_link"><i
                                                    class="far fa-copy me-2"></i>Copy</button>
                                        </div>
                                    </div>

                                    {{-- <div class="form-group">
                                                <div class="row">
                                                    <div class="col-12 text-right">
                                                        <button type="submit" class="btn btn-gradient-warning px-4 mt-0 mb-0"> Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div> --}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @if ($authUser->role == 0)
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card">
                    {{-- <h4> Survey Settings </h4> --}}
                    <div class="card-body">
                        <h4 class="header-title mt-0"> Estimator Survey Settings </h4>
                        <form action="{{ route('setting.save') }}" method="POST" id="general_profile"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="last_name"> Currency Symbol </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i data-feather="dollar-sign"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" id="currency_symbol"
                                                value="{{ setting('estimate_currency_symbol', $authUser->id) ?? '' }}"
                                                placeholder="$, £, €" aria-label="estimate_currency_symbol"
                                                aria-describedby="button-addon4" name="estimate_currency_symbol">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="last_name"> Primary Color </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i data-feather="dollar-sign"></i>
                                                </span>
                                            </div>
                                            {{-- {{ dd() }} --}}
                                            <input type="text" class="form-control color-input"
                                                id="estimator_primary_color"
                                                value="{{ setting('estimator_primary_color', $authUser->id) ?? '#ED2846' }}"
                                                aria-describedby="button-addon4" name="estimator_primary_color">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="last_name">  Thank You Page URL </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i data-feather="globe"></i>
                                                </span>
                                            </div>
                                            {{-- {{ dd() }} --}}
                                            <input type="text" class="form-control" placeholder="https//example.com"
                                                id="thank_you_page_url"
                                                value="{{ setting('thank_you_page_url', $authUser->id) ?? $loc->location ?? '' }}"
                                                aria-describedby="button-addon4" name="thank_you_page_url">
                                        </div>
                                    </div>
                                </div>

                                 <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="last_name"> Last Slide Button Text </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i data-feather="globe"></i>
                                                </span>
                                            </div>
                                            {{-- {{ dd() }} --}}
                                            <input type="text" class="form-control" placeholder="Contact us for this estimate"
                                                id="last_slide_button_text"
                                                value="{{ setting('last_slide_button_text', $authUser->id) ?? 'Contact us for this estimate' }}"
                                                aria-describedby="button-addon4" name="last_slide_button_text">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="last_name"> Minimum Fees </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i data-feather="dollar-sign"></i>
                                                </span>
                                            </div>

                                            <input type="number" class="form-control" placeholder="min fee"
                                                id="min_fee"
                                                value="{{ setting('min_fee', $authUser->id) ?? 0 }}"
                                                aria-describedby="button-addon4" name="min_fee">
                                        </div>
                                    </div>
                                </div>


                                {{-- can write other settings things here --}}
                            </div>

                            <!-- <hr class="py-3">
                            <h4 class="text-danger text-center">Map Slide Instructions</h4>
                            {{-- map instructions --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="map_instructions">Map Slide Instructions</label>
                                        <textarea class="form-control " rows="4" name="map_slide_instructions"
                                            placehpolder="write your instructions to show on the map slide">
                                        {!! trim(setting('map_slide_instructions', $authUser->id) ?? '') !!}
                                    </textarea>
                                    </div>
                                </div>
                            </div> -->

                            {{-- error messages in survey --}}

                            @php
                                $error_messages = [
                                    'Fence type' => 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.',
                                    'Fence style' => 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.',
                                    'Fence height' => 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.',
                                    'Fence ft price' => 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.',
                                    'Price range' => 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.',
                                    'Gate type price' => 'An error has occurred while using the fence estimator tool. If this issue persists, please contact your fence company via phone or email to get your estimate. We apologize for the inconvenience.',
                                ];
                            @endphp

                            <hr class="py-3">
                            <!-- <h4 class="text-danger text-center"> Slider Error Messages </h4>
                            <div class="row">
                                @foreach ($error_messages as $key => $value)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="{{ $key }}">{{ $key }} Not Found Error Message
                                            </label>
                                            <textarea class="form-control " rows="2"
                                                name="error_message_{{ strtolower(str_replace(' ', '', $key)) }}"
                                                placehpolder="write the error message if {{ $key }} is missing during survey completions">
                                            {{ trim(setting('error_message_' . strtolower(str_replace(' ', '', $key)), $authUser->id) ?? $value) }}
                                        </textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div> -->

                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button type="submit" class="btn btn-gradient-warning px-4 mt-0 mb-0"> Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if ($authUser->separate_location == 1)
        <div class="row text-center">
            <div class="col-md-12">
                <div class="form-group">
                    <p class="font-weight-bold fs-4">
                        <a class="btn btn-primary" href="{{ $connecturl }}">
                           Connect With CRM Location
                        </a>
                    </p>
                </div>
            </div>
        </div>
        @endif
    @endif

@endsection
@section('js')
    <script src="{{ asset('plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/pages/jquery.clipboard.init.js') }}"></script>
    <script src="{{ asset('assets/js/iframe-resizer.js') }}"></script>

    <script>
        // $(document).ready(function() {
        //     var location_id = "{{ $authUser->location }}";

        //     var url = "{{ route('estimator.index') }}/"+location_id;
        //     var iframe = '<iframe src="' + url + '"  frameborder="0" style="border:none;width:100%;" scrolling="no" id="'+location_id+'"></iframe>';

        //     $('#estimator_iframe').val(iframe);
        // });
    </script>
@endsection
