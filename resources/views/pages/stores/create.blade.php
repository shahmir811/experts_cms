@extends('layouts.app')

@section('title', 'Create Store')

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <style>
/* Match Select2 with Bootstrap 5 input height */
.select2-container .select2-selection--single {
  height: 38px !important; /* Match Bootstrap 5 input height */
  padding: 6px 12px;
  border: 1px solid #ced4da;
  border-radius: 0.375rem; /* Same as Bootstrap 5 input */
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
  line-height: 24px; /* vertically center text */
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
  height: 38px;
  top: 0px;
  right: 6px;
}

  </style>
@endpush

@section('content')
<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Create Store
            <a href="{{ route('stores.index') }}" class="btn btn-primary float-end">Back</a>
          </h4>
        </div>
        <div class="card-body">
          <form action="{{ route('stores.store') }}" method="POST">
            @csrf

            {{-- Store Name --}}
            <div class="mb-3">
              <label for="name" class="form-label">Name</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}">
              @error('name')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            {{-- Store Owner --}}
            <div class="mb-3">
              <label for="owner_id" class="form-label">Owner</label>
              <select name="owner_id" class="form-control @error('owner_id') is-invalid @enderror" id="owner_id">
                <option value="">Select Owner</option>
                @foreach ($customers as $customer)
                  <option value="{{ $customer->id }}" {{ old('owner_id') == $customer->id ? 'selected' : '' }}>
                    {{ $customer->name }}
                  </option>
                @endforeach
              </select>
              @error('owner_id')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            {{-- Wallmart Key --}}
            <div class="mb-3">
              <label for="wallmart_key" class="form-label">Wallmart Key</label>
              <div class="input-group">
                <input 
                  type="password" 
                  name="wallmart_key" 
                  class="form-control @error('wallmart_key') is-invalid @enderror" 
                  id="wallmart_key" 
                  value="{{ old('wallmart_key') }}"
                >
                <button 
                  type="button" 
                  class="btn btn-outline-secondary" 
                  id="toggleWallmartKey"
                  tabindex="-1"
                >
                  <i class="bi bi-eye" id="wallmartKeyIcon"></i>
                </button>
                @error('wallmart_key')
                  <div class="invalid-feedback d-block">
                    {{ $message }}
                  </div>
                @enderror
              </div>
            </div>


            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#owner_id').select2({
        placeholder: 'Select Owner',
        allowClear: true
      });
    });

    $(document).ready(function () {
      $('#toggleWallmartKey').on('click', function () {
        const input = $('#wallmart_key');
        const icon = $('#wallmartKeyIcon');
        const type = input.attr('type') === 'password' ? 'text' : 'password';

        input.attr('type', type);
        icon.toggleClass('bi-eye bi-eye-slash');
      });
    });
  </script>
@endpush