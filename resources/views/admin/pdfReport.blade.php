<!DOCTYPE html>
<html>
<head>
  <title>Analytics Report</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h2 { color: #0d6efd; }
    .section { margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
  </style>
</head>
<body>
  <h2>Analytics Report - {{ ucfirst($filter) }} View</h2>

  <div class="section">
    <strong>Total Messages:</strong> {{ $totalMessages }}<br>
    <strong>Answered:</strong> {{ $answeredMessages }}<br>
    <strong>Unanswered:</strong> {{ $unAnsweredMessages }}<br>
    <strong>Most Frequent Category:</strong> {{ $mostCategoryName }}
  </div>

  <div class="section">
    <h4>Category Breakdown</h4>
    <ul>
      <li>Admission: {{ $admissionMessages }}</li>
      <li>Scholarship: {{ $scholarshipMessages }}</li>
      <li>Placement: {{ $placementMessages }}</li>
      <li>General: {{ $generalMessages }}</li>
    </ul>
  </div>

  <div class="section">
    <h4>Inquiry Trend ({{ ucfirst($filter) }})</h4>
    <table>
      <thead>
        <tr>
          <th>Label</th>
          <th>Count</th>
        </tr>
      </thead>
      <tbody>
        @foreach($labels as $index => $label)
        <tr>
          <td>{{ $label }}</td>
          <td>{{ $counts[$index] }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="section">
    <h4>Recent System Logs</h4>
    <table>
      <thead>
        <tr>
          <th>Action</th>
          <th>User</th>
          <th>Time</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentLogs as $log)
        <tr>
          <td>{{ $log->action_type }}</td>
          <td>{{ $log->user->name ?? 'System' }}</td>
          <td>{{ \Carbon\Carbon::parse($log->timestamp)->toDayDateTimeString() }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="3">No logs available.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</body>
</html>
