@extends('layouts.app')

@section('title', 'Products List')

@section('content')

<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h4>Products
            <a href="{{ route('products.upload.form') }}" class="btn btn-primary float-end">Upload Products</a>
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
            <table class="table table-bordered">
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
              <tbody>
                @foreach ($products as $product)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                      <img src="{{ asset($product->image) }}" alt="Product Image" width="100">
                    </td>
                    <td>{{ $product->store->name }}</td>
                    <td>{{ $product->title }}</td>
                    <td>
                      <a href="https://amazon.com/dp/{{ $product->asin }}" target="_blank">{{ $product->asin }}</a>
                    </td>
                    <td>{{ $product->amazon_current_price }}</td>
                    <td>{{ $product->amazon_stock }}</td>
                    <td>
                      <button class="btn btn-primary">Details</button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          {{-- {{ $products->links() }} --}}
          
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
