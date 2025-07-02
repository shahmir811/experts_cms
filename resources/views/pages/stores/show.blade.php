@extends('layouts.app')

@section('title', 'Store Details')

@section('styles')
{{-- 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"> --}}


<style>

  #loading {
    transition: opacity 0.3s ease;
}

  .spinner-border {
      width: 3rem;
      height: 3rem;
  }

    .pagination {
        margin: 0;
    }
    
    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
    
    .page-link {
        color: #0d6efd;
        padding: 0.375rem 0.75rem;
    }
    
    .page-item.disabled .page-link {
        color: #6c757d;
    }
    
    .showing-results {
        color: #6c757d;
        font-size: 0.875rem;
    }
    
    .pagination-sm .page-link {
        font-size: 0.875rem;
    }

</style>


@endsection

@section('content')

<!-- Product Detail Modal -->
<div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="productDetailModalLabel">Product Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="productDetailContent">
        <!-- Content will be loaded here via AJAX -->
        <div class="text-center">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

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

{{-- Product Details --}}
<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Products ({{ $products->total() }})</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Image</th>
                  <th>Title</th>
                  <th>ASIN</th>
                  <th>Price</th>
                  <th>Stock</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($products as $product)
                  <tr>
                    <td>{{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}</td>
                    <td>
                      <img src="{{ asset($product->image) }}" alt="Product Image" width="100">
                    </td>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->asin }}</td>
                    <td>{{ $product->amazon_current_price }}</td>
                    <td>{{ $product->amazon_stock }}</td>
                    <td>
                      <button class="btn btn-info btn-sm view-detail" data-product-id="{{ $product->id }}" data-bs-toggle="modal" data-bs-target="#productModal">
                        <i class="bi bi-eye"></i> Detail
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
          <!-- Pagination Links -->
          <div class="d-flex justify-content-between align-items-center mt-4">
              <div class="showing-results">
                  Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
              </div>
              
              <nav aria-label="Page navigation">
                  <ul class="pagination pagination-sm mb-0">
                      {{-- Previous Page Link --}}
                      @if ($products->onFirstPage())
                          <li class="page-item disabled">
                              <span class="page-link">&laquo; Previous</span>
                          </li>
                      @else
                          <li class="page-item">
                              <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">&laquo; Previous</a>
                          </li>
                      @endif

                      {{-- Pagination Elements --}}
                      @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                          @if ($page == $products->currentPage())
                              <li class="page-item active" aria-current="page">
                                  <span class="page-link">{{ $page }}</span>
                              </li>
                          @else
                              <li class="page-item">
                                  <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                              </li>
                          @endif
                      @endforeach

                      {{-- Next Page Link --}}
                      @if ($products->hasMorePages())
                          <li class="page-item">
                              <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">Next &raquo;</a>
                          </li>
                      @else
                          <li class="page-item disabled">
                              <span class="page-link">Next &raquo;</span>
                          </li>
                      @endif
                  </ul>
              </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')

{{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous"></script>

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

    // Handle detail button clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('view-detail') || 
            e.target.closest('.view-detail')) {
            e.preventDefault();
            const button = e.target.classList.contains('view-detail') ? 
                          e.target : e.target.closest('.view-detail');
            const productId = button.dataset.productId;
            loadProductDetail(productId);
        }
    });
    
    function loadProductDetail(productId) {
        const modal = new bootstrap.Modal(document.getElementById('productDetailModal'));
        const modalContent = document.getElementById('productDetailContent');
        
        // Show loading spinner
        modalContent.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        modal.show();
        
        fetch(`/products/${productId}/detail`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.html) {
                modalContent.innerHTML = data.html;
            } else {
                modalContent.innerHTML = `
                    <div class="alert alert-danger">
                        Error loading product details. Please try again.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading product details:', error);
            modalContent.innerHTML = `
                <div class="alert alert-danger">
                    Error loading product details. Please try again.
                </div>
            `;
        });
    }    

  });


</script>
@endpush
