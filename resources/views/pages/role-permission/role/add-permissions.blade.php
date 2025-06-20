@extends('layouts.app')

@section('title', 'Assign Roles Permission')

@section('content')

  <div class="container mt-5">
    <div class="row">
      <div class="col-md-12">

        @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
        @endif


        <div class="card">
          <div class="card-header">
            <h4>Role : {{ $role->name }}
              <a href="{{ route('roles.index') }}" class="btn btn-danger float-end">Back</a>
            </h4>
          </div>
          <div class="card-body">
            <form action="{{ route('roles.save-permissions', $role->slug) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="row mb-3">

                @error('permissions')
                  <div class="alert alert-danger">
                    {{ $message }}
                  </div>
                @enderror

                <div class="col-md-12">
                  <div class="form-group">
                    <label for="name">Permissions</label>
                    <div class="row">
                      @foreach ($permissions as $permission)
                        <div class="col-md-2">
                          <label class="form-check-label" for="permission-{{ $permission->slug }}">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->slug }}" id="permission-{{ $permission->slug }}" {{ in_array($permission->slug, $role->permissions->pluck('slug')->toArray()) ? 'checked' : '' }}>
                            {{ $permission->name }}
                          </label>
                        </div>
                      @endforeach
                    </div>
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
