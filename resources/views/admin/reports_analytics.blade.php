@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Make sure Bootstrap JS is loaded -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


@php
  $user = Auth::user();
@endphp

<div class="container-fluid mt-4">

   {{-- Filter Options --}}
   <div class="card mb-4">
    <div class="card-body">
      <form method="GET" action="{{ route('admin.reports_analytics') }}" id="filterForm">
        <input type="hidden" name="start_date" id="hidden_start_date">
        <input type="hidden" name="end_date" id="hidden_end_date">
        <input type="hidden" name="start_time" id="hidden_start_time">
        <input type="hidden" name="end_time" id="hidden_end_time">

        <div class="d-flex justify-content-between align-items-end flex-wrap gap-3">
        @php
    $filter = request('filter', 'day');
    $startDate = request('start_date');
    $endDate = request('end_date');
    $customLabel = 'Custom';

    if ($filter === 'custom' && $startDate && $endDate) {
        $startFormatted = \Carbon\Carbon::parse($startDate)->format('M d');
        $endFormatted = \Carbon\Carbon::parse($endDate)->format('M d');
        $customLabel = "Custom ({$startFormatted} - {$endFormatted})";
    }

    $filterOptions = [
        'day' => 'Day',
        'week' => 'Week',
        'month' => 'Month',
        'custom' => $customLabel
    ];
@endphp

<div class="form-group">
  <label for="filterSelect" class="form-label fw-bold">Select Time Filter</label>
  <select id="filterSelect" class="form-select" name="filter">
    @foreach ($filterOptions as $val => $label)
      <option value="{{ $val }}" {{ $filter == $val ? 'selected' : '' }}>{{ $label }}</option>
    @endforeach
  </select>
  <span id="customDateLabel" class="ms-2 text-muted" style="font-size: 0.9rem;">
  @if(request('filter') === 'custom' && request('start_date') && request('end_date'))
    ({{ request('start_date') }} to {{ request('end_date') }})
  @endif
</span>

</div>

<div class="dropdown">
  <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-download"></i> Export
  </button>
  <ul class="dropdown-menu" aria-labelledby="exportDropdown">
    <li>
      <a class="dropdown-item" href="{{ route('admin.reports_analytics.exports', request()->all()) }}">
        <i class="bi bi-file-earmark-pdf-fill text-danger"></i> PDF
      </a>
    </li>
    <li>
      <a class="dropdown-item" href="{{ route('admin.reports_export_csv', request()->all()) }}">
        <i class="bi bi-file-earmark-spreadsheet-fill text-success"></i> CSV
      </a>
    </li>
  </ul>
</div>
        </div>

        {{-- Inline Custom Inputs (Initially Hidden) --}}
        <div id="customInputs" class="row g-3 mt-3 d-none">
          <div class="col-md-3">
            <label for="custom_start_date" class="form-label">Start Date</label>
            <input class="form-control" type="date" id="custom_start_date">
          </div>
          <div class="col-md-3">
            <label for="custom_end_date" class="form-label">End Date</label>
            <input class="form-control" type="date" id="custom_end_date">
          </div>
          <div class="col-md-3">
            <label for="custom_start_time" class="form-label">Start Time</label>
            <input class="form-control" type="time" id="custom_start_time">
          </div>
          <div class="col-md-3">
            <label for="custom_end_time" class="form-label">End Time</label>
            <input class="form-control" type="time" id="custom_end_time">
          </div>
        </div>

        <div id="applyFilterWrapper" class="mt-3 d-none">
  <button type="submit" class="btn btn-primary">Apply Filter</button>
</div>
      </form>
    </div>
  </div>


  <!-- Summary Statistics Bar Chart -->
  <div class="card shadow-sm border-0 rounded-3 mb-4">
    <div class="card-header bg-white border-bottom">
      <h5 class="mb-0 fw-bold text-primary">Summary Statistics</h5>
    </div>
    <div class="card-body" style="height: 350px;">
      <canvas id="summaryStatsChart"></canvas>
    </div>
  </div>

  <!-- Category Counts Pie Chart -->
  <div class="card shadow-sm border-0 rounded-3 mb-4">
    <div class="card-header bg-white border-bottom">
      <h5 class="mb-0 fw-bold text-primary">Inquiry Category Counts</h5>
    </div>
    <div class="card-body" style="height: 350px;">
      <canvas id="categoryCountsChart"></canvas>
    </div>
  </div>

  <!-- Inquiry Trend Chart -->
  <div class="card shadow-sm border-0 rounded-3 mb-4">
    <div class="card-header bg-white border-bottom">
      <h5 class="mb-0 fw-bold text-primary">Inquiry Trend Over Time</h5>
    </div>
    <div class="card-body" style="height: 350px; overflow-x:auto;">
      <div id="queriesChartWrapper">
        <div id="chartLoading" class="text-center d-none">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2">Loading chart data...</p>
        </div>
        <canvas id="queriesChart"></canvas>
      </div>
    </div>
  </div>
  <div class="card mb-4">
  <div class="card-header bg-white border-bottom"><h5 class="fw-bold">Average Messages</h5></div>
  <div class="card-body" style="height: 300px;">
    <canvas id="averageMessagesChart"></canvas>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header bg-white border-bottom"><h5 class="fw-bold">Peak Message Hour</h5></div>
  <div class="card-body" style="height: 300px;">
    <canvas id="peakHourChart"></canvas>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header bg-white border-bottom"><h5 class="fw-bold">Most Active Year</h5></div>
  <div class="card-body" style="height: 300px;">
    <canvas id="yearDistributionChart"></canvas>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header bg-white border-bottom"><h5 class="fw-bold">Messages Per User</h5></div>
  <div class="card-body" style="height: 300px; overflow-x: auto;">
    <canvas id="messagesPerUserChart"></canvas>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header bg-white border-bottom"><h5 class="fw-bold">Dominant Course</h5></div>
  <div class="card-body" style="height: 300px;">
    <canvas id="dominantCourseChart"></canvas>
  </div>
</div>



</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<script>
document.addEventListener('DOMContentLoaded', function () {
  const filterSelect = document.getElementById('filterSelect');
  const customInputs = document.getElementById('customInputs');
  const applyFilterWrapper = document.getElementById('applyFilterWrapper');

  filterSelect.addEventListener('change', function () {
    if (this.value === 'custom') {
      customInputs.classList.remove('d-none');
      applyFilterWrapper.classList.remove('d-none');
    } else {
      customInputs.classList.add('d-none');
      applyFilterWrapper.classList.add('d-none');
      document.getElementById('filterForm').submit();
    }
  });

  document.getElementById('filterForm').addEventListener('submit', function (e) {
    if (filterSelect.value === 'custom') {
      const startDate = document.getElementById('custom_start_date').value;
      const endDate = document.getElementById('custom_end_date').value;
      const startTime = document.getElementById('custom_start_time').value;
      const endTime = document.getElementById('custom_end_time').value;

      // Set hidden fields for backend processing
      document.getElementById('hidden_start_date').value = startDate;
      document.getElementById('hidden_end_date').value = endDate;
      document.getElementById('hidden_start_time').value = startTime;
      document.getElementById('hidden_end_time').value = endTime;

      // Update dropdown text with selected range
      const formatDate = (d) => {
        const options = { month: 'short', day: 'numeric', year: 'numeric' };
        return new Date(d).toLocaleDateString(undefined, options);
      };
      const customText = `Custom (${formatDate(startDate)} ${startTime} - ${formatDate(endDate)} ${endTime})`;
      const customOption = filterSelect.querySelector('option[value="custom"]');
      if (customOption) customOption.textContent = customText;
    }
  });

  // Show loading animation briefly
  const chartLoading = document.getElementById('chartLoading');
  const queriesChart = document.getElementById('queriesChart');
  chartLoading.classList.remove('d-none');
  queriesChart.style.display = 'none';

  setTimeout(() => {
    chartLoading.classList.add('d-none');
    queriesChart.style.display = 'block';

    // Inquiry Trend Bar Chart
    new Chart(queriesChart, {
      type: 'bar',
      data: {
        labels: @json($labels),
        datasets: [{
          label: 'Inquiries',
          data: @json($counts),
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 1500, easing: 'easeOutQuart' },
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, ticks: { precision: 0 } }
        }
      }
    });
  }, 500);

  // Summary Statistics Chart
  new Chart(document.getElementById('summaryStatsChart'), {
    type: 'bar',
    data: {
      labels: ['Total Messages', 'Answered', 'Unanswered', '{{ $mostCategoryName }}'],
      datasets: [{
        label: 'Counts',
        data: [{{ $totalMessages }}, {{ $answeredMessages }}, {{ $unAnsweredMessages }}, {{ $mostCategoryCount }}],
        backgroundColor: [
          'rgba(13, 110, 253, 0.7)',
          'rgba(25, 135, 84, 0.7)',
          'rgba(220, 53, 69, 0.7)',
          'rgba(255, 193, 7, 0.7)'
        ],
        borderColor: [
          'rgba(13, 110, 253, 1)',
          'rgba(25, 135, 84, 1)',
          'rgba(220, 53, 69, 1)',
          'rgba(255, 193, 7, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: { beginAtZero: true, ticks: { precision: 0 } }
      },
      plugins: {
        legend: { display: false }
      }
    }
  });

  new Chart(document.getElementById('averageMessagesChart'), {
  type: 'bar',
  data: {
    labels: ['Daily'],
    datasets: [{
      label: 'Average Messages',
      data: [{{ $avgPerDay }}],
      backgroundColor: '#0d6efd'
    }]
  },
  options: {
    scales: { y: { beginAtZero: true } }
  }
});

// Peak Hour Chart
new Chart(document.getElementById('peakHourChart'), {
  type: 'bar',
  data: {
    labels: @json($peakHour->pluck('hour')),
    datasets: [{
      label: 'Messages',
      data: @json($peakHour->pluck('count')),
      backgroundColor: '#20c997'
    }]
  },
  options: {
    scales: { y: { beginAtZero: true } },
    plugins: {
      title: { display: true, text: 'Messages by Hour' }
    }
  }
});

// Most Active Year Chart
new Chart(document.getElementById('yearDistributionChart'), {
  type: 'pie',
  data: {
    labels: @json($topYearLevel->pluck('year')),
    datasets: [{
      data: @json($topYearLevel->pluck('count')),
      backgroundColor: ['#0dcaf0', '#ffc107', '#fd7e14', '#198754']
    }]
  },
  options: {
    plugins: {
      title: { display: true, text: 'Users by Year Level' }
    }
  }
});

// Messages per User Chart
new Chart(document.getElementById('messagesPerUserChart'), {
  type: 'bar',
  data: {
    labels: @json($messagesPerUser->pluck('user_id')),
    datasets: [{
      label: 'Messages',
      data: @json($messagesPerUser->pluck('count')),
      backgroundColor: '#0d6efd'
    }]
  },
  options: {
    indexAxis: 'y',
    scales: { x: { beginAtZero: true } },
    plugins: {
      title: { display: true, text: 'Messages per User' }
    }
  }
});

// Dominant Course Chart
new Chart(document.getElementById('dominantCourseChart'), {
  type: 'doughnut',
  data: {
    labels: @json($topCourse->pluck('course')),
    datasets: [{
      data: @json($topCourse->pluck('count')),
      backgroundColor: ['#6f42c1', '#20c997', '#ffc107', '#dc3545', '#0d6efd']
    }]
  },
  options: {
    plugins: {
      title: { display: true, text: 'Users by Course' }
    }
  }
});

  // Category Pie Chart
  new Chart(document.getElementById('categoryCountsChart'), {
    type: 'pie',
    data: {
      labels: ['Admission', 'Scholarship', 'Placement', 'General'],
      datasets: [{
        data: [{{ $admissionMessages }}, {{ $scholarshipMessages }}, {{ $placementMessages }}, {{ $generalMessages }}],
        backgroundColor:  [
          'rgba(255, 99, 132, 0.6)',
          'rgba(255, 206, 86, 0.6)',
          'rgba(75, 192, 192, 0.6)',
          'rgba(153, 102, 255, 0.6)',
          'rgba(255, 159, 64, 0.6)',
          'rgba(60, 179, 113, 0.6)'
        ],
        borderColor: 'rgba(255,255,255,1)',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: { boxWidth: 20, padding: 15 }
        }
      }
    }
  });
});
</script>

<!-- Optional AJAX block -->
<script>
$(document).ready(function() {
  const filter = 'week'; // Replace this if dynamic

  $.ajax({
    url: '/reports/ajax-data',
    type: 'GET',
    data: { filter: filter },
    success: function(response) {
      console.log('Stats:', response.stats.totalMessages);
      console.log('Chart labels:', response.chart.labels);
      console.log('Pie data:', response.pie.categoryLabels);
      response.recentLogs.forEach(log => {
        console.log(log.user.name + ": " + log.activity);
      });
    },
    error: function(xhr) {
      console.error('Error fetching report data:', xhr);
    }
  });
});
</script>

@endsection