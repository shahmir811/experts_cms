@extends('layouts.app')

@section('page_title', 'Upload Products')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Upload ASIN Excel File</h4>
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

                    <form method="POST" action="{{ route('products.upload') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        
                        <div class="mb-3">
                            <label for="store_id" class="form-label">Store <span class="text-danger">*</span></label>
                            <input type="hidden" name="store_id" value="{{ $store->id }}">
                            <input type="text" class="form-control" name="store_name" value="{{ $store->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="excel_file" class="form-label">Excel File <span class="text-danger">*</span></label>
                            <input type="file" name="excel_file" id="excel_file" 
                                class="form-control @error('excel_file') is-invalid @enderror" 
                                accept=".xlsx,.xls" required>
                            @error('excel_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div> <!-- Added d-block -->
                            @enderror
                            <small class="form-text text-muted">
                                <a href="{{ asset('samples/KeepaExport.xlsx') }}" download>
                                    Download Sample Excel File
                                </a>
                            </small>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Upload</button>
                            <a href="{{ route('stores.index') }}" class="btn btn-danger">Cancel</a>
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
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    const storeSelect = document.querySelector('#store_id');
    const fileInput = document.querySelector('#excel_file');
    
    // Add required asterisk dynamically
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        const label = form.querySelector(`label[for="${field.id}"]`);
        if (label && !label.innerHTML.includes('*')) {
            label.innerHTML += ' <span class="text-danger">*</span>';
        }
    });

    form.addEventListener('submit', function(event) {
        let isValid = true;
        
        // Reset validation
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        
        // Validate store selection
        if (!storeSelect.value) {
            storeSelect.classList.add('is-invalid');
            const errorDiv = storeSelect.nextElementSibling;
            errorDiv.textContent = 'Please select a store.';
            errorDiv.style.display = 'block';
            isValid = false;
        }
        
        // Validate file input
        if (!fileInput.files || fileInput.files.length === 0) {
            fileInput.classList.add('is-invalid');
            const errorDiv = fileInput.nextElementSibling;
            errorDiv.textContent = 'Please select an Excel file.';
            errorDiv.style.display = 'block';
            isValid = false;
        } else {
            const allowedTypes = [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-excel'
            ];
            if (!allowedTypes.includes(fileInput.files[0].type)) {
                fileInput.classList.add('is-invalid');
                const errorDiv = fileInput.nextElementSibling;
                errorDiv.textContent = 'Only Excel files (.xlsx, .xls) are allowed.';
                errorDiv.style.display = 'block';
                isValid = false;
            }
        }
        
        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        form.classList.add('was-validated');
    });
    
    // Real-time validation for store select
    storeSelect.addEventListener('change', function() {
        if (this.value) {
            this.classList.remove('is-invalid');
            const errorDiv = this.nextElementSibling;
            if (errorDiv) errorDiv.style.display = 'none';
        }
    });
    
    // Real-time validation for file input
    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            this.classList.remove('is-invalid');
            const errorDiv = this.nextElementSibling;
            if (errorDiv) errorDiv.style.display = 'none';
        }
    });
});
</script>
@endsection