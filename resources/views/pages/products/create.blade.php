@extends('layouts.app')

@section('page_title', 'Upload Products')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Upload ASIN Excel File for {{ $store->name }}</h4>
                </div>
                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('products.upload', $store->id) }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="mb-3">
                            <label for="country" class="form-label">Country</label>
                            <select name="country" id="country" class="form-select" required>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ $country->name === 'United States' ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select a country.</div>
                        </div>

                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Choose Excel File</label>
                            <input type="file" name="excel_file" id="excel_file" class="form-control" accept=".xlsx,.xls" required>
                            <div class="invalid-feedback">Please upload a valid Excel file.</div>
                            <small class="form-text text-muted">
                                <a href="{{ asset('samples/KeepaExport-file.xlsx') }}" download>
                                    Sample Excel file
                                </a>
                            </small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Upload</button>
                            <a href="{{ route('stores.view', $store->id) }}" class="btn btn-danger">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Bootstrap form validation
(function () {
    'use strict'
    
    const forms = document.querySelectorAll('.needs-validation')
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
@endsection