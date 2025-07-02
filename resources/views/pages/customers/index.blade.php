@extends('layouts.app')

@section('title', 'Customers List')

@section('styles')
<style>
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
    
    .btn-clear-search {
        margin-left: 5px;
    }
</style>
@endsection

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
            <h4>Customers
              <a href="{{ route('customers.create') }}" class="btn btn-primary float-end">
                <i class="bi bi-plus-circle-fill"></i> Add Customer
              </a>
            </h4>
          </div>
          <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="{{ route('customers.index') }}" method="GET">
                      <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by customer name" value="{{ request('search') }}">
                        <button class="btn btn-outline-secondary" type="submit">
                            <i class="bi bi-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('customers.index') }}" class="btn btn-outline-danger btn-clear-search">
                                <i class="bi bi-x-circle-fill"></i> Clear
                            </a>
                        @endif
                    </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ ($customers->currentPage() - 1) * $customers->perPage() + $loop->iteration }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->city }}</td>
                                <td>{{ $customer->state }}</td>
                                <td>
                                  <a href="{{ route('customers.show', $customer->slug) }}" class="btn btn-warning mr-5">
                                    <i class="bi bi-eye-fill"></i> View
                                  </a>
                                  <a href="{{ route('customers.edit', $customer->slug) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                  </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Updated Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="showing-results">
                    Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} results
                </div>
                
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Previous Page Link --}}
                        @if ($customers->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">&laquo; Previous</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $customers->previousPageUrl() }}" rel="prev">&laquo; Previous</a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                            @if ($page == $customers->currentPage())
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
                        @if ($customers->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $customers->nextPageUrl() }}" rel="next">Next &raquo;</a>
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
</div>

@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Clear search functionality
    const clearSearchBtn = document.querySelector('.btn-clear-search');
    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = "{{ route('customers.index') }}";
      });
    }
  });
</script>
@endpush