@extends('layouts.app')

@section('title', 'Create Customer')

@section('content')

<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Create Customer
            <a href="{{ route('customers.index') }}" class="btn btn-danger float-end">Back</a>
          </h4>
        </div>
        <div class="card-body">
          <form action="{{ route('customers.store') }}" method="POST">
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
                  <label for="phone">Phone</label>
                  <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" value="{{ old('phone') }}">
                  @error('phone')
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
                  <label for="city">City</label>
                  <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" id="city" value="{{ old('city') }}">
                  @error('city')
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
                  <label for="state">State</label>
                  <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" id="state" value="{{ old('state') }}">
                  @error('state')
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
                  <label for="address">Address</label>
                  <textarea name="address" class="form-control @error('address') is-invalid @enderror" id="address" rows="3">{{ old('address') }}</textarea>
                  @error('address')
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
