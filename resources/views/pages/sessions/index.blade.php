@extends('layouts.app')

@section('title', 'Sessions List')

@section('content')

<div class="container mt-5">
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <div class="card-header">
          <h4>Sessions</h4>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-12">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">User ID</th>
                    <th scope="col">User Name</th>
                    <th scope="col">IP Address</th>
                    <th scope="col">User Agent</th>
                    <th scope="col">Last Activity</th>
                    <th scope="col">Login Time</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($sessions as $session)
                    <tr @if($session->is_current) class="table-success" @endif>
                      <th scope="row">{{ $loop->iteration }}</th>
                      <td>{{ $session->user_id }}</td>
                      <td>{{ $session->user_name }}</td>
                      <td>{{ $session->ip_address }}</td>
                      <td>{{ $session->user_agent }}</td>
                      <td>{{ $session->last_activity }}</td>
                      <td>{{ $session->login_time }}</td>
                      <td>
                        {{ $session->status }}
                        @if($session->is_current)
                          <span class="badge bg-success">My Session</span>
                        @endif
                      </td>
                      <td>
                        @can('delete session')
                          @unless($session->is_current)
                            <form action="{{ route('active-sessions.destroy', $session->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this session?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger mx-2">
                                <i class="fa fa-trash"></i> Delete
                              </button>
                            </form>
                          @endunless
                        @endcan
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

@endsection
