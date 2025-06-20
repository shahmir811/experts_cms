@extends('layouts.app')

@section('title', 'Create User')

@section('content')

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Create User
              <a href="{{ route('users.index') }}" class="btn btn-danger float-end">Back</a>
            </h4>
          </div>
          <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
              @csrf
              <div class="row mb-3">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}">
                    @error('name')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}">
                    @error('email')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" value="{{ old('password') }}">
                    @error('password')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="role">Role</label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror" id="role">
                      <option value="">Select Role</option>
                      @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                          {{ $role->name }}
                        </option>
                      @endforeach
                    </select>
                    @error('role')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-12">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection