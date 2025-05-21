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
    p { margin: 8px 0; font-style: italic; color: #333; }
  </style>
</head>
<body>
  <h2>Report and Analytics - {{ ucfirst($filter) }} View</h2>

  {{-- Summary Section --}}
  <div class="section">
    <strong>Total Messages:</strong> {{ $totalMessages }}<br>
    <strong>Answered:</strong> {{ $answeredMessages }}<br>
    <strong>Unanswered:</strong> {{ $unAnsweredMessages }}<br>
    <strong>Most Frequent Category:</strong> {{ $mostCategoryName }} ({{ $mostCategoryCount }})<br>

    <p>
      <strong>Conclusion:</strong>
      Out of a total of {{ $totalMessages }} messages, {{ $answeredMessages }} have been answered.
      {{ $unAnsweredMessages }} messages remain unanswered. The category "<em>{{ $mostCategoryName }}</em>" dominates with {{ $mostCategoryCount }} messages, indicating a strong demand for support in that area.
    </p>
  </div>

  <div class="section">
  <h4>Category Breakdown</h4>
  <ul>
    @foreach($categoryMessages as $category => $count)
      <li>{{ $category }}: {{ $count }}</li>
    @endforeach
  </ul>

  @php
    // Sort categories by count descending, preserve keys
    arsort($categoryMessages);
    $dominantCategory = array_key_first($categoryMessages);
    $dominantCount = $categoryMessages[$dominantCategory];
    $others = $categoryMessages;
    unset($others[$dominantCategory]);
  @endphp

  <p>
    <strong>Conclusion:</strong>
    {{ $dominantCategory }}-related messages dominate with {{ $dominantCount }} inquiries.
    @if(count($others) > 0)
      This is followed by
      @foreach($others as $cat => $cnt)
        {{ $cat }} ({{ $cnt }})@if (!$loop->last), @elseif(count($others) > 1) and @endif
      @endforeach,
      confirming {{ strtolower($dominantCategory) }} as a primary concern.
    @endif
  </p>
</div>
  {{-- Inquiry Trend --}}
  <div class="section">
    <h4>Inquiry Trend ({{ ucfirst($filter) }})</h4>
    <table>
      <thead>
        <tr><th>Label</th><th>Count</th></tr>
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

    @php
      $countsArray = $counts->toArray();
      $maxCount = $counts->max();
      $minCount = $counts->min();
      $maxIndex = array_search($maxCount, $countsArray);
      $minIndex = array_search($minCount, $countsArray);
    @endphp

    <p>
      <strong>Conclusion:</strong>
      The peak of inquiries occurred on <em>{{ $labels[$maxIndex] }}</em> with {{ $maxCount }} messages.
      The lowest occurred on <em>{{ $labels[$minIndex] }}</em> with {{ $minCount }} messages.
    </p>
  </div>

  {{-- Category Pie Distribution --}}
  <div class="section">
    <h4>Category Distribution (Pie Data)</h4>
    <table>
      <thead>
        <tr><th>Category</th><th>Count</th></tr>
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

    <p>
      <strong>Conclusion:</strong>
      "<em>{{ $mostCategoryName }}</em>" leads with {{ $mostCategoryCount }} messages. Other categories:
      @php $otherCategoryOutput = []; @endphp
      @foreach($categoryLabels as $index => $category)
        @if($category !== $mostCategoryName)
          @php $otherCategoryOutput[] = '"' . $category . '" (' . $categoryCounts[$index] . ')'; @endphp
        @endif
      @endforeach
      {!! implode(', ', $otherCategoryOutput) !!}.
    </p>
  </div>

{{-- User Insights --}}
<div class="section">
  <h4>User Insights</h4>

  <h5>Top Year Levels</h5>
  <table>
    <thead><tr><th>Year Level</th><th>Count</th></tr></thead>
    <tbody>
      @foreach($topYearLevel as $year)
      <tr>
        <td>{{ $year->year_level }}</td>
        <td>{{ $year->count }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  @php
    $topYear = $topYearLevel->first();
  @endphp
  <p>
    <strong>Conclusion:</strong> Overall, the most represented year level is
    <em>{{ $topYear->year_level }}</em> with {{ $topYear->count }} users.
  </p>

  <h5>Year Level with the most Inquiries</h5>
  <table>
    <thead><tr><th>Year Level</th><th>Count</th></tr></thead>
    <tbody>
      @foreach($topYearLevel as $year)
      <tr>
        <td>{{ $year->year_level }}</td>
        <td>{{ $year->count }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <p>
    <strong>Conclusion:</strong> The year level with the most inquiries is
    <em>{{ $topYear->year_level }}</em> with {{ $topYear->count }} messages.
  </p>

  <h5>Top Courses</h5>
  <table>
    <thead><tr><th>Course</th><th>Count</th></tr></thead>
    <tbody>
      @foreach($topCourse as $course)
      <tr>
        <td>{{ $course->course_name }}</td>
        <td>{{ $course->count }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  @php
    $topCourseName = $topCourse->first();
  @endphp
  <p>
    <strong>Conclusion:</strong> The course with the highest number of users is
    <em>{{ $topCourseName->course_name }}</em> with {{ $topCourseName->count }} users.
  </p>

  <h5>Course with the most Inquiries</h5>
  <table>
    <thead><tr><th>Course</th><th>Count</th></tr></thead>
    <tbody>
      @foreach($topCourse as $course)
      <tr>
        <td>{{ $course->course_name }}</td>
        <td>{{ $course->count }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <p>
    <strong>Conclusion:</strong> The course with the most inquiries is
    <em>{{ $topCourseName->course_name }}</em> with {{ $topCourseName->count }} messages.
  </p>
</div>

{{-- Peak Times --}}
<div class="section">
  <h4>Peak Message Times (By Hour)</h4>
  <table>
    <thead><tr><th>Hour</th><th>Message Count</th></tr></thead>
    <tbody>
      @foreach($peakHour as $peak)
      <tr>
        <td>{{ $peak['hour'] }}</td>
        <td>{{ $peak['count'] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  @php
    $peakHourTop = collect($peakHour)->sortByDesc('count')->first();
  @endphp
  <p>
    <strong>Conclusion:</strong> The peak messaging time is
    <em>{{ $peakHourTop['hour'] }}</em> with {{ $peakHourTop['count'] }} messages.
  </p>

  <h4>Peak Message Days</h4>
  <table>
    <thead><tr><th>Day</th><th>Message Count</th></tr></thead>
    <tbody>
      @foreach($peakDay as $day)
      <tr>
        <td>{{ $day->day }}</td>
        <td>{{ $day->count }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>

  @php
    $peakDayTop = collect($peakDay)->sortByDesc('count')->first();
  @endphp
  <p>
    <strong>Conclusion:</strong> The peak messaging day is
    <em>{{ $peakDayTop->day }}</em> with {{ $peakDayTop->count }} messages.
  </p>
</div>

</body>
</html>
