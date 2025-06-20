@extends('layouts.app')

@section('title', 'Backups List')

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
          <h4>Backups List</h4>
        </div>
        <div class="card-body">
          <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Size (KB)</th>
                    <th>Last Modified</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($backups as $backup)
                    <tr>
                        <td>{{ $backup['name'] }}</td>
                        <td>{{ number_format($backup['size'] / 1024, 2) }}</td>
                        <td>{{ \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->toDayDateTimeString() }}</td>
                        <td>
                            <form method="POST" action="{{ route('backups.delete') }}">
                                @csrf
                                <input type="hidden" name="path" value="{{ $backup['path'] }}">
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this backup?')">Delete</button>
                            </form>
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

@endsection
