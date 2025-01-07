@extends('layouts.app')

@section('title', 'Edit Ft. Avaiable')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ft_available.list') }}">Ft. Avaiable</a></li>
                        <li class="breadcrumb-item active">Edit Ft. Avaiable</li>

                    </ol>
                </div>
                <h4 class="page-title">Edit Ft. Avaiable</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!-- end page title end breadcrumb -->

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('ft_available.save', $data->id) }}" method="POST" class="card-box">
                        @csrf
                        {{-- <div class="form-group row">
                            <div class="col-md-12">
                                <label class="">For User *</label>
                                <select name="user_id" class="select2 form-control mb-3 custom-select select2-hidden-accessible" style="width: 100%; height:36px;" tabindex="-1" aria-hidden="true">
                                    @foreach ($users as $user)
                                        <option value={{ $user->id }} {{ $user->id == $data->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                           </div> --}}
                         <div class="form-group row">
                             <div class="col-md-12">
                                 <label for="ft_available_name">Ft. Avaiable Name *</label>
                                 <input type="text" placeholder="Name" class="form-control @error('ft_available_name') is-invalid @enderror" name="ft_available_name" value="{{ old('ft_available_name', $data->ft_available_name) }}" id="ft_available_name" autocomplete="off">
                                 @error('ft_available_name')
                                 <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                                 @enderror
                             </div>
                        </div>

                        <div class="form-group">
                            <a href="{{ route('ft_available.list') }}" class="btn btn-danger btn-sm text-light px-4 mt-3 float-right mb-0 ml-2">Cancel</a>
                            <button type="submit" class="btn btn-primary btn-sm text-light px-4 mt-3 float-right mb-0">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
<script>
    function togglePass() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>
@endsection
