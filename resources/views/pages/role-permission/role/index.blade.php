@extends('layouts.app')

@section('title', 'Roles List')

@push('styles')
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
</style>
@endpush

@section('content')

  @include('pages.role-permission.nav-links')

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
            <h4>Roles
              <a href="{{ route('roles.create') }}" class="btn btn-primary float-end">Add Role</a>
            </h4>
          </div>
          <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="{{ route('roles.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by role name" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                            @if(request('search'))
                                <a href="{{ route('roles.index') }}" class="btn btn-outline-danger btn-clear-search">Clear</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Role Name</th>
                      @role('super-admin|admin')
                        <th scope="col">Action</th>
                      @endrole
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($roles as $role)
                      <tr>
                        <th scope="row">{{ ($roles->currentPage() - 1) * $roles->perPage() + $loop->iteration }}</th>
                        <td>{{ $role->name }}</td>

                        @role('super-admin|admin')
                        <td>
                          @can(['create role', 'view role'])
                          <a href="{{ route('roles.give-permissions', $role->slug) }}" class="btn btn-warning mx-2"><i class="fa fa-edit"></i>Add/Edit Permissions</a>
                          @endcan

                          @can('update role')
                          <a href="{{ route('roles.edit', $role->slug) }}" class="btn btn-primary mx-2"><i class="fa fa-edit"></i>Edit</a>
                          @endcan

                          @can('delete role')
                          <form action="{{ route('roles.destroy', $role->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this role?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger mx-2">
                              <i class="fa fa-trash"></i> Delete
                            </button>
                          </form>
                          @endcan

                        </td>
                        @endrole
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                
                <!-- Add pagination links -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="showing-results">
                        Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} of {{ $roles->total() }} results
                    </div>
                    
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($roles->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo; Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $roles->previousPageUrl() }}" rel="prev">&laquo; Previous</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($roles->getUrlRange(1, $roles->lastPage()) as $page => $url)
                                @if ($page == $roles->currentPage())
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
                            @if ($roles->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $roles->nextPageUrl() }}" rel="next">Next &raquo;</a>
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
  </div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Clear search button
        const clearBtn = document.querySelector('.btn-clear-search');
        if (clearBtn) {
            clearBtn.addEventListener('click', function(e) {
                e.preventDefault();
                window.location.href = "{{ route('roles.index') }}";
            });
        }
    });
</script>
@endpush