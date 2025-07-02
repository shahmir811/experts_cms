@extends('layouts.app')

@section('title', 'Products List')

@section('styles')

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"> --}}


<style>

  #loading {
    transition: opacity 0.3s ease;
}

  .spinner-border {
      width: 3rem;
      height: 3rem;
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

<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Products
            {{-- <a href="{{ route('products.upload.form') }}" class="btn btn-primary float-end">Upload Products</a> --}}
          </h4>
        </div>
        <div class="card-body">

          <!-- Search form -->
          <div class="row mb-4">
            <div class="col-md-6">
              <form method="get" action="{{ route('products.index') }}" id="searchForm">
                <div class="input-group">
                  <input type="text" 
                        class="form-control" 
                        name="search" 
                        id="searchInput"
                        placeholder="Search by title or ASIN"
                        value="{{ request('search') }}">
                    <button class="btn btn-primary search-button" type="submit">
                      <i class="fas fa-search"></i> Search
                    </button>
                  @if(request('search'))
                    <a href="{{ route('products.index') }}" class="btn btn-secondary" id="clearSearch">
                      <i class="bi bi-x-circle"></i> Clear
                    </a>
                  @endif
                </div>
              </form>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered" id="productsTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Image</th>
                  <th>Store</th>
                  <th>Title</th>
                  <th>ASIN</th>
                  <th>Price</th>
                  <th>Stock</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="productsContainer">
                @include('pages.products.partials.products_rows', ['products' => $products])
              </tbody>
            </table>
          </div>
          
          <div id="loading" class="text-center my-4" style="display: none;">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p>Loading more products...</p>
          </div>

          <div id="noMoreProducts" class="text-center my-4" style="display: none;">
            <p class="text-muted">You've reached the end of the product list.</p>
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
document.addEventListener('DOMContentLoaded', function() {
    let isLoading = false;
    let hasMore = true;
    let nextPageUrl = '{{ $products->nextPageUrl() }}';
    
    // Debounce function to prevent rapid firing
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
    
    const handleScroll = debounce(function() {
        if (isLoading || !hasMore) return;
        
        const scrollPosition = window.innerHeight + window.scrollY;
        const pageHeight = document.body.offsetHeight;
        const threshold = 200;
        
        if (scrollPosition >= pageHeight - threshold) {
            loadMoreProducts();
        }
    }, 200);
    
    window.addEventListener('scroll', handleScroll);
    
    function loadMoreProducts() {
        if (!nextPageUrl) {
            hasMore = false;
            return;
        }
        
        isLoading = true;
        document.getElementById('loading').style.display = 'block';
        
        // Ensure URL has proper query parameters
        let loadUrl = nextPageUrl;
        if (!loadUrl.includes('?')) {
            loadUrl += '?ajax=1';
        } else {
            loadUrl += '&ajax=1';
        }
        
        fetch(loadUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            // First check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new TypeError("Response is not JSON");
            }
            return response.json();
        })
        .then(data => {
            if (data.html && data.html.trim()) {
                document.getElementById('productsContainer').insertAdjacentHTML('beforeend', data.html);
                nextPageUrl = data.next_page;
                hasMore = !!data.next_page;
                
                if (!hasMore) {
                    document.getElementById('noMoreProducts').style.display = 'block';
                }
            } else {
                hasMore = false;
                document.getElementById('noMoreProducts').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error loading more products:', error);
            hasMore = false;
            document.getElementById('loading').innerHTML = 
                '<p class="text-danger">Error loading products. Please refresh the page.</p>';
        })
        .finally(() => {
            isLoading = false;
            document.getElementById('loading').style.display = 'none';
        });
    }
    
    // Handle search form submission
    document.getElementById('searchForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        isLoading = true;
        document.getElementById('loading').style.display = 'block';
        
        const formData = new FormData(this);
        formData.append('ajax', '1');
        
        fetch(this.action, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: new URLSearchParams(formData)
        })
        .then(response => {
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new TypeError("Response is not JSON");
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('productsContainer').innerHTML = data.html;
            nextPageUrl = data.next_page;
            hasMore = !!data.next_page;
        })
        .catch(error => {
            console.error('Search error:', error);
            // Fallback to normal page reload if AJAX fails
            this.submit();
        })
        .finally(() => {
            isLoading = false;
            document.getElementById('loading').style.display = 'none';
        });
    });
    
    // Handle clear search
    document.getElementById('clearSearch')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.getElementById('searchInput').value = '';
        document.getElementById('searchForm').dispatchEvent(new Event('submit'));
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