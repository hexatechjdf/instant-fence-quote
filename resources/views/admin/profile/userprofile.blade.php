@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="float-right">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">User Profile</li>
                    </ol>
                </div>
                <h4 class="page-title">User Profile</h4>
            </div><!--end page-title-box-->
        </div><!--end col-->
    </div>
    <!-- end page title end breadcrumb -->
  <div class="row">
      <div class="col-md-8">
          <div class="card">
              <div class="card-body">
                  <h4 class="header-title mt-0"> General Settings</h4>
                  <form action="{{route('profile.save')}}" method="POST"  id="general_profile" enctype="multipart/form-data">
                      @csrf
                      <div class="row">
                      <div class="col-md-7">
                          <div class="form-group">
                              <label for="last_name"> Name *</label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                                  </div>
                                 
                                  <input type="text" placeholder="Name"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name',$user->name??'') }}" id="name" autocomplete="off">
                                @error('name')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>
                          </div>
                          <div class="form-group">
                              <label for="email">Email *</label>
                              <div class="input-group">
                                  <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                  </div>
                                 <input type="text" placeholder="Email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email',$user->email??'') }}" id="email" autocomplete="off">
                                @error('email')
                                    <span class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                              </div>
                          </div>
                          @if(auth()->user()->role == 0)
                          <div class="form-group">
                            <label for="email"> Rogue CRM API Key  <strong class="ml-3"> ( Note: </strong><small class="text-danger"> Insert only If You want send the estimate informations to the Rogue CRM  </small>) </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <textarea  name="ghl_api_key" class="form-control" rows="3"  autocomplete="off" autofocus="autofocus" >{{ old('ghl_api_key',$user->ghl_api_key ??'')}}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email"> Unique Estimator Survey Id *   <strong class="ml-3"> ( Note: </strong><small class="text-danger"> It will be the unique identifier for User's estimator survey </small>)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                {{-- <input type="text" name="location" class="form-control"  autocomplete="off" autofocus="autofocus" value=""> --}}
                                <textarea placeholder="Unique Estimator Survey Id" class="form-control @error('location') is-invalid @enderror" name="location"
                                 id="location"> {{$user->location}}</textarea>
                            @error('location')
                                <span class="invalid-feedback">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            </div>
                        </div>
                        
                        @endif
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-12 text-right">
                                      <button type="submit" class="btn btn-gradient-warning px-4 mt-0 mb-0"> Save </button>
                                  </div>
                              </div>
                          </div>
                      </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="card">
              <div class="card-body">
                  <h4 class="header-title mt-0"> Security </h4>
                  <form action="{{route('password.save')}}" method="POST"  id="">
                      @csrf
                      <div class="form-group">
                          <label for="current_password">Current Password *</label>
                          <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-key"></i></span>
                              </div>
                              <input type="password" name="current_password" class="form-control"  autocomplete="off" autofocus="autofocus">
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="new_password">New Password *</label>
                          <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                              </div>
                              <input type="password" name="password" class="form-control"  autocomplete="off" autofocus="autofocus">
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="new_password">Confirm Password *</label>
                          <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                              </div>
                              <input type="password" name="confirm_password" class="form-control"  autocomplete="off" autofocus="autofocus">
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="row">
                              <div class="col-12 text-right">
                                  <button type="submit" class="btn btn-gradient-warning px-4 mt-0 mb-0"> Change  </button>
                              </div>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
@endsection
@section('js')
    <script>

    </script>
@endsection
