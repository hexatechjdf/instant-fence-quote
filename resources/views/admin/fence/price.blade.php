@extends('layouts.app')
@section('title', 'Set Prices')
@section('css')
    <style>
        label.form-heading {
            color: red;
            /* border-bottom: 2px solid black; */
            padding-bottom: 5px;
            font-weight: bold;
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
                        <li class="breadcrumb-item"><a href="{{ route('fence.list') }}">Fence</a></li>
                        <li class="breadcrumb-item active">Set Prices</li>
                    </ol>
                </div>
                <h4 class="page-title">Set Prices </h4>
            </div>
            <!--end page-title-box-->
        </div>
        <!--end col-->
    </div>
    <!-- end page title end breadcrumb -->

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-body">
                    <h2 class="page-title text-center">{{ $fence->category->name }} - {{ $fence->fence_name }}</h2>
                    <div class="col-md-4">
                        <button class="abc btn btn-info" onclick="submitall()">Save All Prices</button>
                    </div>
                    <div class="molvi">
                        @foreach ($ft_availables as $ft)
                            @php
                                $single = ft_price($fence->id, $ft->ft_available->id, 'single');
                                $double = ft_price($fence->id, $ft->ft_available->id, 'double');
                            @endphp
                            <h4 class="pas-title pt-3 pb-2 text-primary"><span
                                    class="pr-2 text-dark">{{ $loop->iteration }}.</span>{{ $ft->ft_available->ft_available_name }}
                            </h4>
                            <form class="saveftprice  saveftprice{{ $ft->ft_available->id }}"
                                data-sid="{{ $ft->ft_available->id }}">
                                @csrf
                                <input type="hidden" value="{{ $ft->ft_available->id }}" name="ft_available_id" />
                                <input type="hidden" value="{{ $fence->id }}" name="fence_id" />

                                <div class="row pt-1 pb-4">
                                    <div class="col-md-3 col-sm-3">
                                        <label for="price">Fence Price *</label>
                                        <input type="text" placeholder="Price"
                                            class="form-control @error('price') is-invalid @enderror" name="price"
                                            value-old="{{ old('price', $ft->price ?? null) }}"
                                            value="{{ old('price', $ft->price ?? null) }}" id="fprice"
                                            autocomplete="off">
                                        @error('price')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <label for="price">Price Range *</label>
                                        <input type="text" placeholder="Range"
                                            class="form-control @error('range') is-invalid @enderror" name="range"
                                            value-old="{{ old('range', $ft->range ?? null) }}"
                                            value="{{ old('range', $ft->range ?? null) }}" id="frange"
                                            autocomplete="off">
                                        @error('range')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                     
                                    <div class="col-md-3 col-sm-3">
                                        <label for="is_min_fee" class="form-heading"> Apply Min Fee ? </label>
                                        <input type="checkbox" id="is_min_fee" name="is_min_fee" class="active-check"
                                            checked-old="{{ $ft && $ft->is_min_fee == 1 ? 'checked' : '' }}"
                                            @if (isset($ft->is_min_fee)) {{ $ft->is_min_fee == 1 ? 'checked' : '' }} @endif
                                            value="1" style="margin-top: 10px; margin-left:25px;transform:scale(1.5)">
                                            <br/>
                                            <small class="text-danger">If fence calculation less than ${{ setting('min_fee', auth()->user()->id) ?? 0 }} - show estimation based on min fee</small>
                                    </div>
                                    
                                    <div class="col-md-3 col-sm-3">
                                        <label for="price">Save Price *</label><br>
                                        <input type="submit"
                                            class="btn submitall btn-{{ $ft && $ft->price ? 'success' : 'primary' }}"
                                            value="{{ $ft->price ? 'Update' : 'Set' }} Price">
                                    </div>
                                    
                                    
                                </div>
                                
                                
                            </form>
                            <form action="{{ route('fence.price.save', $single->id ?? null) }}" method="POST"
                                class="savesingleprice  savesingleprice{{ $single->id ?? $ft->ft_available->id }}"
                                data-sid="{{ $single->id ?? $ft->ft_available->id }}">
                                @csrf
                                <input type="hidden" value="{{ $ft->ft_available->id }}" name="ft_available_id" />
                                <input type="hidden" value="{{ $fence->id }}" name="fence_id" />
                                <input type="hidden" name="type" value="single">

                                <div class="form-group row">
                                    <div class="col-md-3 col-sm-3">
                                        <label for="price" class="form-heading">Type </label><br>
                                        <span class="pl-3">Single</span>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <label for="price " class="form-heading">Price *</label>
                                        <input type="text" placeholder="Price"
                                            class="form-control @error('price') is-invalid @enderror" name="price"
                                            value="{{ old('price', $single->ft_price ?? null) }}"
                                            value-old="{{ old('price', $single->ft_price ?? null) }}" id="sprice"
                                            autocomplete="off">
                                        @error('price')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <label for="price  " class="form-heading">is Active *</label><br>
                                        <input type="checkbox" name="is_active" class="active-check"
                                            checked-old="{{ $single && $single->is_active == 1 ? 'checked' : '' }}"
                                            @if (isset($single->is_active)) {{ $single->is_active == 1 ? 'checked' : '' }} @endif
                                            value="1" style="margin-top: 10px; margin-left:25px;transform:scale(1.5)">
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <label class="form-heading">Save Record</label><br>
                                        <input type="submit"
                                            class="btn submitall btn-{{ $single && $single->ft_price ? 'success' : 'primary' }}"
                                            value="{{ $single && $single->ft_price ? 'Update' : 'Set' }} Price">
                                    </div>
                                </div>
                            </form>
                            {{-- double type --}}
                            <form class="savedoubleprice savedoubleprice{{ $double->id ?? $ft->ft_available->id }}"
                                method="POST" action="{{ route('fence.price.save', $double->id ?? null) }}"
                                data-sid="{{ $double->id ?? $ft->ft_available->id }}" method="POST">
                                @csrf
                                <input type="hidden" value="{{ $ft->ft_available->id }}" name="ft_available_id" />
                                <input type="hidden" value="{{ $fence->id }}" name="fence_id" />
                                <input type="hidden" name="type" value="double">
                                <div class="form-group row">
                                    <div class="col-md-3 col-sm-3">
                                        <span class="pl-3">Double</span>
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        {{-- <label for="price">Price *</label> --}}
                                        <input type="text" placeholder="Price"
                                            class="form-control @error('price') is-invalid @enderror" name="price"
                                            value="{{ old('price', $double->ft_price ?? null) }}"
                                            value-old="{{ old('price', $double->ft_price ?? null) }}" id="dprice"
                                            autocomplete="off">
                                        @error('price')
                                            <span class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    {{-- <div class="col-md-3 d-none">
                                            <label for="price">Range *</label>
                                            <input type="text" placeholder="Range"
                                                    class="form-control @error('range') is-invalid @enderror" name="range"
                                                    value="{{ old('range',$double->ft_range??null) }}" id="range" autocomplete="off">
                                                @error('range')
                                                    <span class="invalid-feedback">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                        </div> --}}
                                    <div class="col-md-3 col-sm-3">
                                        <input type="checkbox" name="is_active" class="active-check"
                                            checked-old="{{ $double && $double->is_active == 1 ? 'checked' : '' }}"
                                            @if (isset($double->is_active)) {{ $double->is_active == 1 ? 'checked' : '' }} @endif
                                            value="1"
                                            style="margin-top: 10px; margin-left:25px;transform:scale(1.5)">
                                    </div>
                                    <div class="col-md-3 col-sm-3">
                                        <input type="submit"
                                            class="btn submitall btn-{{ $double && $double->ft_price ? 'success' : 'primary' }}"
                                            value="{{ $double && $double->ft_price ? 'Update' : 'Set' }} Price">
                                    </div>
                                </div>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <style>
        button.abc {
            position: fixed;

            right: 0;
            transform: rotate(270deg);
        }
    </style>
    <script>
        function submitall() {
            $('.submitall').click();
        }
        $('.saveftprice').on('submit', function(e) {
            $('#frange', $(this)).removeClass('is-invalid');
            $('#fprice', $(this)).removeClass('is-invalid');
            e.preventDefault();
            let num = $('#fprice', $(this)).val();

            if (num == '') {
                 
                return false;
            }
            
            if (isNaN(Number(num))) {
                $('#fprice', $(this)).addClass('is-invalid');
                $('#fprice', $(this)).focus();
                toastr.error('Price Must be a Number.');
                return false;
            }

            let num1 = $('#frange', $(this)).val();
            let check = $('#is_min_fee', $(this));
            let checkedval = check.prop('checked') ? 'checked' : '';
            let oldcheck = check.attr('checked-old');
            if (num == $('#fprice', $(this)).attr('value-old') && num1 == $('#frange', $(this)).attr('value-old') && checkedval == oldcheck) {
                return;
            }
            if (num != '' && num1 == '') {
                $('#frange', $(this)).addClass('is-invalid');
                $('#frange', $(this)).focus();
                toastr.error('Range required.');
                return false;
            }
            if (isNaN(Number(num1))) {
                $('#frange', $(this)).addClass('is-invalid');
                $('#frange', $(this)).focus();
                toastr.error('Range Must be a Number.');
                return false;
            }
            var dt = $(this);
            var sid = $(this).attr('data-sid');
            $.ajax({
                type: 'POST',
                url: "{{ route('fence.fencetype.price') }}",
                data: $('.saveftprice' + sid).serialize(),
                success: function(data) {
                    $('#fprice', dt).attr('value-old', num);
                    $('#frange', dt).attr('value-old', num1);
                     check.attr('checked-old', checkedval);
                    toastr.success(data);
                }
            });
        })
    </script>
    <script>
        $('.savesingleprice').on('submit', function(e) {
            e.preventDefault();
            $('#sprice', $(this)).removeClass('is-invalid');
            let num2 = $('#sprice', $(this)).val();
            let check = $('.active-check', $(this));
            let checkedval = check.prop('checked') ? 'checked' : '';
            let oldcheck = check.attr('checked-old');
            if (num2 == '' || (num2 == $('#sprice', $(this)).attr('value-old') && checkedval == oldcheck)) {
      
                return;
            }
            if (isNaN(Number(num2))) {
                $('#sprice', $(this)).addClass('is-invalid');
                toastr.error('Price Must be a Number.');
                $('#sprice', $(this)).focus();
                return false;
            }
            var dt = $(this);
            var sid = $(this).attr('data-sid');
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $('.savesingleprice' + sid).serialize(),
                success: function(data) {
                    $('#sprice', dt).attr('value-old', num2);
                    check.attr('checked-old', checkedval);
                    toastr.success(data);
                }
            });
        })
    </script>
    <script>
        $('.savedoubleprice').on('submit', function(e) {
            e.preventDefault();
            $('#dprice', $(this)).removeClass('is-invalid');
            let num3 = $('#dprice', $(this)).val();
            let check = $('.active-check', $(this));
            let checkedval = check.prop('checked') ? 'checked' : '';
            let oldcheck = check.attr('checked-old');
            if (num3 == '' || (num3 == $('#dprice', $(this)).attr('value-old') && checkedval == oldcheck)) {
                
                return;
            }
            if (isNaN(Number(num3))) {
                $('#dprice', $(this)).addClass('is-invalid');
                $('#dprice', $(this)).focus();
                toastr.error('Price Must be a Number.');
                return false;
            }
            var dt = $(this);
            var sid = $(this).attr('data-sid');
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $('.savedoubleprice' + sid).serialize(),
                success: function(data) {
                    $('#dprice', dt).attr('value-old', num3);
                    check.attr('checked-old', checkedval);
                    toastr.success(data);
                }
            });
        })
    </script>
@endsection
