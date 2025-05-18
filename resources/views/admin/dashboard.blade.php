@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<div class="container-fluid mt-4">
  <!-- Top Row: Greeting and Filter -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h4 class="fw-bold text-primary mb-1">Welcome back, {{ $user->name }}!</h4>
      <p class="text-muted mb-0">Here’s an overview of recent student inquiries.</p>
    </div>
    <div class="btn-group" role="group" aria-label="Filter Time">
      @php
        $filters = ['day' => 'Day', 'week' => 'Week', 'month' => 'Month', 'all' => 'All Time'];
      @endphp
      @foreach ($filters as $val => $label)
        <input type="radio" class="btn-check" name="timeFilter" id="{{ $val }}" value="{{ $val }}" autocomplete="off" {{ $filter == $val ? 'checked' : '' }}>
        <label class="btn btn-outline-primary" for="{{ $val }}">{{ $label }}</label>
      @endforeach
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="card border-primary shadow-sm rounded-3 mb-3">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-chat-left-dots-fill fs-2 me-3 text-primary"></i>
          <div>
            <h5 class="mb-0 text-primary">{{ $totalMessages }}</h5>
            <small class="text-muted">Total Messages</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card border-success shadow-sm rounded-3 mb-3">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-check-circle-fill fs-2 me-3 text-success"></i>
          <div>
            <h5 class="mb-0 text-success">{{ $answeredMessages }}</h5>
            <small class="text-muted">Answered Messages</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card border-danger shadow-sm rounded-3 mb-3">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-x-circle-fill fs-2 me-3 text-danger"></i>
          <div>
            <h5 class="mb-0 text-danger">{{ $totalUsers }}</h5>
            <small class="text-muted">Total Users</small>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-3">
      <div class="card border-warning shadow-sm rounded-3 mb-3">
        <div class="card-body d-flex align-items-center">
          <i class="bi bi-tags-fill fs-2 me-3 text-warning"></i>
          <div>
            <h5 class="mb-0 text-warning">{{ $mostCategoryName }}</h5>
            <small class="text-muted">Most Frequent Category</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Charts Section -->
  <div class="row g-4 mb-4" style="margin-top: 10px;margin-bottom: 20px;">
    <div class="col-md-6">
      <div class="card shadow-sm border-0 rounded-3 h-100 mb-3">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0 text-primary fw-bold">Inquiry Category Distribution</h5>
        </div>
        <div class="card-body">
          <h6 class="text-muted text-center mb-2">Based on Category</h6>
          <div style="height: 350px;">
            <canvas id="categoryPieChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card shadow-sm border-0 rounded-3 h-100 mb-3">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0 text-primary fw-bold">Inquiry Trend</h5>
        </div>
        <div class="card-body">
          <h6 class="text-muted text-center mb-2">Number of Inquiries</h6>
          <div style="overflow-x: auto; width: 100%;">
            <canvas id="queriesChart" width="800" height="350"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Logs Section -->
  <div class="card shadow-sm border-0 rounded-3 mb-4" style="margin-top: 30px;margin-bottom: 20px;">
    <div class="card-header bg-white border-bottom">
      <h5 class="mb-0 text-primary fw-bold">Recent System Logs</h5>
    </div>
    <div class="card-body p-0">
      <ul class="list-group list-group-flush">
        @forelse($recentLogs as $log)
          <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="me-auto">
              <div class="fw-semibold">{{ $log->action_type }}</div>
              <small class="text-secondary">By: {{ $log->user->name ?? 'System' }}</small>
            </div>
            <small class="text-muted">{{ \Carbon\Carbon::parse($log->timestamp)->format('F j, Y, g:i A') }}</small>
          </li>
        @empty
          <li class="list-group-item text-center text-muted">No recent logs available.</li>
        @endforelse
      </ul>
    </div>
  </div>
</div>

<script>
Chart.register({
  id: 'noDataPlugin',
  beforeDraw(chart) {
    const datasets = chart.data.datasets;
    const hasData = datasets.some(dataset => dataset.data.length > 0 && dataset.data.some(val => val > 0));

    if (!hasData) {
      const ctx = chart.ctx;
      const width = chart.width;
      const height = chart.height;

      chart.clear();
      ctx.save();
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.font = '20px sans-serif';
      ctx.fillStyle = '#666';
      ctx.fillText('No data for today', width / 2, height / 2);
      ctx.restore();
    }
  }
});

document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll('input[name="timeFilter"]').forEach(radio => {
    radio.addEventListener('change', function () {
      window.location.href = "{{ route('admin.dashboard') }}?filter=" + this.value;
    });
  });

  // Determine chart type based on filter
  const filter = "{{ $filter }}";
  const isAllTime = filter === 'all';
  const chartType = isAllTime ? 'bar' : 'bar';

  const ctx = document.getElementById('queriesChart').getContext('2d');

  new Chart(ctx, {
    type: chartType,
    data: {
      labels: @json($labels),
      datasets: [{
        label: 'Inquiries',
        data: @json($counts),
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 2,
        fill: false,
        tension: 0.3, // smooth line for line chart
        pointRadius: chartType === 'line' ? 4 : 0,
        pointHoverRadius: chartType === 'line' ? 6 : 0,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: {
        duration: 1500,
        easing: 'easeOutQuart'
      },
      plugins: {
        legend: {
          display: false
        },
        noDataPlugin: {} // activate the no data plugin
      },
      scales: {
        x: {
    
          ticks: {
            maxRotation: 45,
            minRotation: 30,
            autoSkip: true,
            maxTicksLimit: 12,
            font: { size: 12 }
          },
          grid: {
            display: false
          }
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Number of Inquiries',
            font: { size: 14, weight: 'bold' }
          },
          ticks: {
            precision: 0,
            font: { size: 12 }
          },
          grid: {
            color: 'rgba(0,0,0,0.1)',
            borderDash: [5, 5]
          }
        }
      }
    }
  });

  // Pie chart remains mostly the same, just add noDataPlugin as well
  new Chart(document.getElementById('categoryPieChart'), {
    type: 'pie',
    data: {
      labels: @json($categoryLabels),
      datasets: [{
        label: 'Categories',
        data: @json($categoryCounts),
        backgroundColor: [
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
          position: 'bottom'
        },
        noDataPlugin: {}
      }
    }
  });
});
</script>
@endsection
