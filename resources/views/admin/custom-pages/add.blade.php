@extends('layouts.app')
@section('title', 'Custom Page')

<style>
    .abc {
        font-family: Arial, Helvetica, sans-serif;
        color: #f9f9FA
    }
</style>
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        @if (auth()->user()->role == 0)
                            <li class="breadcrumb-item"><a href="{{ route('user.list') }}">Company</a></li>
                        @else
                            <li class="breadcrumb-item"><a href="#"> Custom Page </a></li>
                        @endif
                    </ol>
                </div>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!-- end page title end breadcrumb -->


    <div class="row" id="mfsn">
        <div class="col-md-12" id="clone1">
            <div class="card ">
                <form action="{{ route('custom-pages.save') }}" method="POST" class="card-box" id="">
                    @csrf
                    <div class="card-body">
                        <h4 class="page-title mb-3 pg-title"> Add New Page </h4>
                        <input type="hidden" name="type" value="page">
                        <div id="account1">
                            @if (auth()->user()->role == 1)
                                <div class="form-group row">
                                    <div class="col-md-6 pt-3 pb-1 text-left d-none">
                                        {{-- show to all checkbox --}}
                                        <input type="checkbox" name="show_to_all" id="show_to_all" value="1"
                                        style="transform: scale(1.3);" checked>
                                    <label for="account_name">Will be visible to all companies</label>
                                    </div>
                                    <div class="col-md-6 pt-3 pb-1 text-right">
                                        {{-- show to all checkbox --}}
                                        <input type="checkbox" name="is_iframe" id="is_iframe" value="1"
                                            style="transform: scale(1.3);">
                                        <label for="account_name"> Add As Iframe </label>
                                    </div>
                                     <div class="col-md-6 pt-3 pb-1 text-right">
                                    {{-- show outside of the dropdown --}}
                                    <input type="checkbox" name="is_dd_item" id="is_dd_item" value="1"
                                        style="transform: scale(1.3);">
                                    <label for="account_name"> Show as dropdown item </label>
                                </div>
                                </div>
                                
                            @endif
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="page_name">Name</label>
                                    <input type="text" class="form-control @error('page_name') is-invalid @enderror"
                                        name="page_name" placeholder="Page Name" id="page_name" autocomplete="off">
                                    @error('page_name')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row " id="pg-details">
                                <div class="col-md-12">
                                    <label for="page_description">Page Details</label>
                                    <textarea class="form-control editor @error('page_description') is-invalid @enderror" name="page_description"
                                        placeholder="Description" id="page_description" rows="15">
                                    </textarea>
                                    @error('page_description')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row" style="display: none" id="iframe_link">
                                <div class="col-md-12">
                                    <label for="account_name">Iframe Source URL</label>
                                    <input type="text" class="form-control @error('link') is-invalid @enderror"
                                        name="link" placeholder="Iframe Source URL" id="link" autocomplete="off">
                                    @error('link')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>



                        </div>
                        <div class="form-group">
                            <button type="submit"
                                class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0 ">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#show_to_all').click(function() {
                if ($(this).is(':checked')) {
                    $('#select_comp').hide();
                } else {
                    $('#select_comp').show();
                }
            });

            $('#is_iframe').click(function() {
                if ($(this).is(':checked')) {
                    $('#pg-details').hide();
                    $('#iframe_link').show();
                } else {
                    $('#pg-details').show();
                    $('#iframe_link').hide();
                }
            });
        });
    </script>

@endsection
