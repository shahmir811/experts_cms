@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>Edit Role
              <a href="{{ route('roles.index') }}" class="btn btn-danger float-end">Back</a>
            </h4>
          </div>
          <div class="card-body">
            <form action="{{ route('roles.update', $role->slug) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="row mb-3">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="name">Role Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ $role->name }}">
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