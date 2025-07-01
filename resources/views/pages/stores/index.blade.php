@extends('layouts.app')

@section('title', 'Stores List')

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
          <h4>Stores
            <a href="{{ route('stores.create') }}" class="btn btn-primary float-end">Add Store</a>
          </h4>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <form action="{{ route('stores.index') }}" method="GET">
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Search by store name" value="{{ request('search') }}">
                  <button class="btn btn-outline-secondary" type="submit">Search</button>
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
                  <th>Owner</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($stores as $store)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $store->name }}</td>
                    <td>{{ $store->owner->name }}</td>
                    <td>
                      <a href="{{ route('stores.show', $store->slug) }}" class="btn btn-warning mr-5">View</a>
                      <a href="{{ route('stores.edit', $store->slug) }}" class="btn btn-primary">Edit</a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          {{ $stores->links() }}  
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
