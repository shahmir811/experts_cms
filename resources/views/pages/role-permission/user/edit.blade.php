@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Edit User
            <a href="{{ route('users.index') }}" class="btn btn-danger float-end">Back</a>
          </h4>
        </div>
        <div class="card-body">
          <form action="{{ route('users.update', $user->slug) }}" method="POST">
            @csrf
            @method('PUT')
            
            {{-- Name --}}
            <div class="mb-3">
              <label for="name">Name</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name', $user->name) }}">
              @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
              <label for="email">Email</label>
              <input type="email" readonly name="email" class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email', $user->email) }}">
              @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
              <label for="password">Password</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
              @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Role --}}
            <div class="mb-3">
              <label for="role">Role</label>
              <select name="role" class="form-control @error('role') is-invalid @enderror">
                <option value="">Select Role</option>
                @foreach ($roles as $role)
                  <option value="{{ $role->name }}"
                          {{ old('role', $user->getRoleNames()->first()) === $role->name ? 'selected' : '' }}>
                    {{ $role->name }}
                  </option>
                @endforeach
              </select>
              @error('role')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Direct Permissions --}}
            <div class="mb-3">
              <label>Extra Permissions (not via Role)</label>
              <div class="row">
                @foreach ($permissions as $permission)
                  <div class="col-md-4">
                    <div class="form-check">
                      @php
                        $inherited = $rolePermissions->contains($permission->name);
                        $isChecked = $directPermissions->contains($permission->name);
                      @endphp
                      <input
                        class="form-check-input"
                        type="checkbox"
                        name="permissions[]"
                        value="{{ $permission->name }}"
                        id="perm_{{ $permission->id }}"
                        {{ $inherited ? 'checked disabled' : ($isChecked ? 'checked' : '') }}
                      >
                      <label class="form-check-label {{ $inherited ? 'text-muted' : '' }}" for="perm_{{ $permission->id }}">
                        {{ $permission->name }}
                        @if($inherited)
                          <small class="text-muted">(via role)</small>
                        @endif
                      </label>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>


            {{-- Submit --}}
            <div class="mb-3">
              <button type="submit" class="btn btn-primary">Update</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
