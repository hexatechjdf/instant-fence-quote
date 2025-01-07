@extends('layouts.app')
@section('title', 'Permissions')
@section('css')
    <style>
        h1 {
            font-family: sans-serif;
            font-size: 20px;
            text-align: center;
            color: #0a0;
        }

        h2 {
            font-family: sans-serif;
            font-size: 16px;
            text-align: center;
            color: #0a0;
        }

        #proof {
            border: 1px solid #ccc;
            background-color: #fff;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 2px 2px 8px 0px #999;
        }

        .cl-custom-check {
            display: none;
        }

        .cl-custom-check+.cl-custom-check-label {
            /* Unchecked style  */
            background-color: #ccc;
            color: #fff;
            padding: 5px 10px;
            font-family: sans-serif;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border-radius: 4px;
            display: inline-block;
            margin: 0 10px 10px 0;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transition: all 0.6s ease;
        }

        .cl-custom-check:checked+.cl-custom-check-label {
            /* Checked style  */
            background-color: #0a0;
            -webkit-backface-visibility: hidden;
            backface-visibility: hidden;
            transform: rotateY(360deg);
            /* append custom text in text */
        }
    </style>
@endsection
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Permissions</li>
                    </ol>
                </div>
                <h4 class="page-title">Permissions</h4>
            </div>
            <!--end page-title-box-->
        </div>

        <!--end col-->
    </div>


    <div class="row">
        <div class="col-md-12 text-right">
            {{-- back arrow --}}

            <a href="{{ route('product.list') }}" class="btn btn-danger my-3">
                <i data-feather="arrow-left" class="icon-dual icon-xs"></i>
                Back to products</a>
        </div>
    </div>
    <form action="{{ route('product.permission.save') }}">
        @csrf

        <div id="proof">
            {{-- permission card --}}

            <div class="card">
                <div class="card-header">
                    <h1>Permissions ( {{ $product->name ?? '' }} )</h1>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="" class="py-2">Click to Grant/Deny the permissions</label>
                                <div class="row">
                                    @foreach ($permissions as $key => $permission)
                                        <div class="col-md-3 form-group">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input cl-custom-check"
                                                    id="webhook-{{ $key }}" name="{{ $key }}"
                                                    value="{{ $permission }}">
                                                <label class="form-check-label cl-custom-check-label"
                                                    for="webhook-{{ $key }}">{{ breakCamelCase($key) }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-center">
                            <div class="form-group">
                                <input type="submit" class="btn btn-primary" id="save-permissions"
                                    value="Save Permissions">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="" </form>

        @endsection

        @section('js')
            <script>
                $(document).ready(function() {
                    //if value is 1 then make background green
                    $('.cl-custom-check').each(function() {
                        if ($(this).val() == '1') {
                            console.log($(this).val());
                            $(this).closest('.form-check').find('.cl-custom-check-label').css('background-color',
                                '#0a0');
                        }
                    });
                })

                //if the value is 1 then 0 else 1
                $('.cl-custom-check').change(function() {
                    if ($(this).val() == '1') {
                        $(this).closest('.form-check').find('.cl-custom-check-label').css('background-color', '#ccc');
                        $(this).val('0');
                    } else {
                        $(this).closest('.form-check').find('.cl-custom-check-label').css('background-color', '#0a0');
                        $(this).val('1');
                    }
                });

                //insert true for checked and false for unchecked and put them in object
                var permissions = {
                    @foreach ($permissions as $key => $permission)
                        '{{ $key }}': '{{ $permission }}',
                    @endforeach
                };

                //send ajax request to save the permissions
                $('#save-permissions').on('click', function(e) {
                    e.preventDefault();
                    $('.loading').show();
                    var formData = {};
                    $('.cl-custom-check').each(function() {
                        if ($(this).val() == '1') {
                            formData[$(this).attr('name')] = true;
                        } else {
                            formData[$(this).attr('name')] = false;
                        }
                    });
                    formData['permissions'] = JSON.stringify(formData);
                    $.ajax({
                        url: "{{ route('product.permission.save', $id) }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            permissions: formData
                        },
                        success: function(data) {
                            toastr.success(data)
                        },
                        error: function(data) {
                            toastr.error(data)
                        },
                        complete: function() {
                            $('.loading').hide();
                        }

                    });
                });
            </script>
        @endsection
