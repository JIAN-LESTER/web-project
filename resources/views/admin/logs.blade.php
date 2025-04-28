@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 fw-bold">User Logs</h1>

    <form method="GET" action="{{ route('admin.logs') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="input-field" placeholder="Search by user, action, or time..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="table-responsive">
        <table class="table table-hover align-middle text-start">

                <thead class="table-primary">
                    <tr>
                        <th>Logs ID</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                          <td>{{ $log->logID }}</td>
                            <td class="fw-semibold">{{ $log->user->name ?? 'Unknown User' }}</td>
                           
                            <td>{{ $log->action_type }}</td>
                            <td class="text-start">
  {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->timestamp)
      ->subHours(8)
      ->timezone('Asia/Manila')
      ->format('l, F j, Y - h:i A') }}
</td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">No logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $logs->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection