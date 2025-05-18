<!DOCTYPE html>
<html>
<head>
  <title>Report and Analytics</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h2 { color: #0d6efd; }
    .section { margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
    ul { list-style-type: none; padding-left: 0; }
  </style>
</head>
<body>
  <h2>Report and Analytics - {{ ucfirst($filter) }} View</h2>

  <div class="section">
    <strong>Total Messages:</strong> {{ $totalMessages }}<br>
    <strong>Answered:</strong> {{ $answeredMessages }}<br>
    <strong>Unanswered:</strong> {{ $unAnsweredMessages }}<br>
    <strong>Most Frequent Category:</strong> {{ $mostCategoryName }} ({{ $mostCategoryCount }})<br>

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
    <h4>Category Distribution (Pie Data)</h4>
    <table>
      <thead>
        <tr>
          <th>Category</th>
          <th>Count</th>
        </tr>
      </thead>
      <tbody>
        @foreach($categoryLabels as $index => $category)
        <tr>
          <td>{{ $category }}</td>
          <td>{{ $categoryCounts[$index] }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="section">
    <h4>User Insights</h4>
   

    <h5>Top Year Levels</h5>
    <table>
      <thead>
        <tr><th>Year Level</th><th>Count</th></tr>
      </thead>
      <tbody>
        @foreach($topYearLevel as $year)
        <tr>
          <td>{{ $year->year_level }}</td>
          <td>{{ $year->count }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <h5>Top Courses</h5>
    <table>
      <thead>
        <tr><th>Course</th><th>Count</th></tr>
      </thead>
      <tbody>
        @foreach($topCourse as $course)
        <tr>
          <td>{{ $course->course_name }}</td>
          <td>{{ $course->count }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="section">
    <h4>Peak Message Times (By Hour)</h4>
    <table>
      <thead>
        <tr><th>Hour</th><th>Message Count</th></tr>
      </thead>
      <tbody>
        @foreach($peakHour as $peak)
        <tr>
          <td>{{ $peak['hour'] }}</td>
          <td>{{ $peak['count'] }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <h4>Peak Message Days</h4>
    <table>
      <thead>
        <tr><th>Day</th><th>Message Count</th></tr>
      </thead>
      <tbody>
        @foreach($peakDay as $day)
        <tr>
          <td>{{ $day->day }}</td>
          <td>{{ $day->count }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  

</body>
</html>
