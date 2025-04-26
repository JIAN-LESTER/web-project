@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4 fw-bold">User Logs</h1>

    <form method="GET" action="{{ route('admin.logs') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by user, action, or time..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <div class="bg-white p-4 rounded shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>User</th>
                        <th>Message ID</th>
                        <th>Action</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="fw-semibold">{{ $log->user->name ?? 'Unknown User' }}</td>
                            <td>{{ $log->logID }}</td>
                            <td>{{ $log->action }}</td>
                            <td>{{ \Carbon\Carbon::parse($log->timestamp)->setTimezone('Asia/Manila')->format('l, F j, Y - h:i A') }}</td>
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
