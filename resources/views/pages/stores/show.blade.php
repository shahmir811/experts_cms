@extends('layouts.app')

@section('title', 'Store Details')

@section('content')
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
          <h4 class="mb-0">
            <i class="bi bi-shop me-2"></i>Store Details
          </h4>
        </div>
        <div class="card-body p-4">
          <div class="row mb-3">
            <div class="col-4 fw-bold text-muted">
              <i class="bi bi-type me-1"></i>Name:
            </div>
            <div class="col-8">{{ $store->name }}</div>
          </div>

          <div class="row mb-3">
            <div class="col-4 fw-bold text-muted">
              <i class="bi bi-person-fill me-1"></i>Owner:
            </div>
            <div class="col-8">
              {{ $store->owner ? $store->owner->name : 'N/A' }}
            </div>
          </div>

          {{-- Wallmart Key with Eye and Copy Buttons --}}
          <div class="row mb-3 align-items-center">
            <div class="col-4 fw-bold text-muted">
              <i class="bi bi-key-fill me-1"></i>Wallmart Key:
            </div>
            <div class="col-6" id="wallmartKeyText">
              {{ substr($store->wallmart_key, 0, 4) . str_repeat('*', strlen($store->wallmart_key) - 4) }}
            </div>
            <div class="col-2 text-end d-flex justify-content-end gap-2">
              {{-- Toggle Visibility Button --}}
              <button 
                class="btn btn-sm btn-outline-secondary border-0" 
                id="toggleWallmartKey" 
                title="Show/Hide"
              >
                <i class="bi bi-eye" id="wallmartKeyIcon"></i>
              </button>

              {{-- Copy to Clipboard Button --}}
              <button 
                class="btn btn-sm btn-outline-secondary border-0" 
                id="copyWallmartKey" 
                title="Copy to Clipboard"
              >
                <i class="bi bi-clipboard" id="clipboardIcon"></i>
              </button>
            </div>
          </div>


          <div class="row mb-3">
            <div class="col-4 fw-bold text-muted">
              <i class="bi bi-calendar-check me-1"></i>Created At:
            </div>
            <div class="col-8">{{ $store->created_at->format('d M Y') }}</div>
          </div>

          <div class="row mb-3">
            <div class="col-4 fw-bold text-muted">
              <i class="bi bi-calendar-event me-1"></i>Updated At:
            </div>
            <div class="col-8">{{ $store->updated_at->format('d M Y') }}</div>
          </div>
        </div>
        <div class="card-footer text-end bg-light rounded-bottom-4">
          <a href="{{ route('stores.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left-circle me-1"></i>Back to List
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('toggleWallmartKey');
    const copyBtn = document.getElementById('copyWallmartKey');
    const iconEl = document.getElementById('wallmartKeyIcon');
    const textEl = document.getElementById('wallmartKeyText');
    const clipboardIcon = document.getElementById('clipboardIcon');

    const fullKey = @json($store->wallmart_key);
    const maskedKey = fullKey.slice(0, 4) + '*'.repeat(fullKey.length - 4);
    let visible = false;

    toggleBtn.addEventListener('click', () => {
      visible = !visible;
      textEl.textContent = visible ? fullKey : maskedKey;
      iconEl.classList.toggle('bi-eye');
      iconEl.classList.toggle('bi-eye-slash');
    });

    copyBtn.addEventListener('click', () => {
      navigator.clipboard.writeText(fullKey).then(() => {
        clipboardIcon.classList.replace('bi-clipboard', 'bi-clipboard-check');
        setTimeout(() => {
          clipboardIcon.classList.replace('bi-clipboard-check', 'bi-clipboard');
        }, 1500);
      });
    });
  });
</script>
@endpush
