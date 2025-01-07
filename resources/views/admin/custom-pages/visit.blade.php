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
                        <li class="breadcrumb-item active">{{ $page->name }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ $page->name }}</h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>

    <div class="row">
        <div class="col-md-12 text-right">
            {{-- open publically --}}
            <a href="{{ route('custom-pages.public', $page->slug) }}" class="btn btn-gradient-primary px-4 mt-0 mb-3"><i
                    class="mdi mdi-plus-circle-outline mr-2 p-4"></i>Open In Public View</a>
        </div>
    </div>
    @if ($page->type == 'page')
        {!! $page->description !!}
    @else
        <iframe src="{{ $page->link }}" style="border:none;width:100%;height: 100vh; "></iframe>
    @endif
@endsection
