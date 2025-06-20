@extends('layouts.app')

@section('title', 'Users List')

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
            <h4>Users
              <a href="{{ route('users.create') }}" class="btn btn-primary float-end">Add User</a>
            </h4>
          </div>
          <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="{{ route('users.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by name or email" value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                            @if(request('search'))
                                <a href="{{ route('users.index') }}" class="btn btn-outline-danger btn-clear-search">Clear</a>
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
                      <th scope="col">Name</th>
                      <th scope="col">Email</th>
                      <th scope="col">Status</th>
                      <th scope="col">Role</th>
                      <th scope="col">Extra Permissions</th>
                      @role('super-admin|admin')
                        <th scope="col">Action</th>
                      @endrole
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($users as $user)
                      <tr>
                        <th scope="row">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                          <form action="{{ route('users.toggleStatus', $user->id) }}" method="POST" class="d-inline toggle-status-form">
                              @csrf
                              @method('PATCH')
                              <div class="form-check form-switch">
                                  <input class="form-check-input status-switch" 
                                        type="checkbox" 
                                        role="switch"
                                        data-user-name="{{ $user->name }}"
                                        {{ $user->deleted_at ? '' : 'checked' }}
                                  >
                              </div>
                          </form>
                        </td>
                        <td>
                          <span class="badge bg-success">{{ $user->getRoleNames()->first() }}</span>
                        </td>
                        <td>
                          {{-- @foreach ($user->permissions as $permission)
                            <span class="badge bg-primary">{{ $permission->name }}</span>
                          @endforeach --}}
                          @php
                            $rolePermissions = $user->roles->flatMap(function ($role) {
                              return $role->permissions->pluck('name');
                            })->unique();

                            $extraPermissions = $user->getDirectPermissions()->pluck('name');
                          @endphp

                          <span class="badge bg-primary"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="{{ $extraPermissions->isNotEmpty() ? $extraPermissions->implode(', ') : 'No extra permissions' }}">
                            {{ $extraPermissions->count() }}
                          </span>
                        </td>
                        @role('super-admin|admin')
                        <td>
                          @can('update user')
                          <a href="{{ route('users.edit', $user->slug) }}" class="btn btn-primary"><i class="fa fa-edit"></i>Edit</a>
                          @endcan
                          @can('delete user')
                          <form action="{{ route('users.destroy', $user->slug) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
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
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                    </div>
                    
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($users->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo; Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">&laquo; Previous</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                @if ($page == $users->currentPage())
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
                            @if ($users->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">Next &raquo;</a>
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
  document.addEventListener('DOMContentLoaded', function () {
    // Only add clear search event if the button exists
    const clearSearchBtn = document.querySelector('.btn-clear-search');
    if (clearSearchBtn) {
      clearSearchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = "{{ route('users.index') }}";
      });
    }
    
    // Delegate the status switch event to the document
    document.addEventListener('change', function(e) {
      if (e.target.classList.contains('status-switch')) {
        const form = e.target.closest('form');
        const userName = e.target.dataset.userName;
        const isChecked = e.target.checked;
        const confirmation = confirm(
          isChecked 
            ? `Do you want to activate ${userName}?` 
            : `Do you want to deactivate ${userName}?`
        );

        if (confirmation) {
          form.submit();
        } else {
          e.target.checked = !isChecked;
        }
      }
    });
  });
</script>
@endpush
