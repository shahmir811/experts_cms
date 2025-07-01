@extends('layouts.app')

@section('title', 'Customers List')

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
              <a href="{{ route('customers.create') }}" class="btn btn-primary float-end">Add Customer</a>
            </h4>
          </div>
          <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="{{ route('customers.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by customer name" value="{{ request('search') }}">
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
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->city }}</td>
                                <td>{{ $customer->state }}</td>
                                <td>
                                  <a href="{{ route('customers.show', $customer->slug) }}" class="btn btn-warning mr-5">View</a>
                                  <a href="{{ route('customers.edit', $customer->slug) }}" class="btn btn-primary">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $customers->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
