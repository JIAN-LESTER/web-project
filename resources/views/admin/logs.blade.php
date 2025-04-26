@extends('layouts.app')

@section('content')
<h1 class="mb-3">User Logs</h1>
<form method="GET" action="{{ route('logs.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search logs..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>
<div class="container bg-white p-4 rounded shadow-sm"> <!-- Added background color and styling -->




    <div class="table-responsive">
        <table class="table table-striped table-bordered"> <!-- Added border for better visibility -->
            <thead class="table-primary">
                <tr>
                    <th>User ID</th>
                    <th>Surname</th>
                    <th>IP Address</th>
                    <th>Action</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{ $log->user_id }}</td>
                        <td>{{ $log->login_id }}</td>
                        <td>{{ $log->ip_address }}</td>
                        <td>{{ $log->action }}</td>
                        <td>{{ \Carbon\Carbon::parse($log->timestamp)->setTimezone('Asia/Manila')->format('l, F j, Y - h:i A')
 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $logs->appends(['search' => request('search')])->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection
