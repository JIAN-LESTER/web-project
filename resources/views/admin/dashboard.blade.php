@extends('layouts.app')

@section('content')
<!-- Include required libraries for charts and icons -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<!-- Google Fonts added for better typography -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  /*
   * Root Variables - Color System
   * Main color palette definition
   */
  :root {
    --primary: #165a36;      /* Dark green as primary color */
    --primary-light: #2a7d4f; /* Lighter green for hover states */
    --primary-dark: #0d4024;  /* Darker green for borders and emphasis */
    --accent: #e8f5e9;        /* Very light green for subtle accents */
    --white: #ffffff;         /* White for text on dark backgrounds */
    --light-gray: #f5f5f5;    /* Light gray for backgrounds */
    --medium-gray: #e0e0e0;   /* Medium gray for borders */
    --dark-gray: #555555;     /* Dark gray for secondary text */
    --success: #43a047;       /* Green for success indicators */
    --danger: #e53935;        /* Red for danger/error indicators */
    --warning: #ff9800;       /* Orange for warnings */
    --info: #165a36;          /* Changed to dark green for info */
  }

  /* Base styling with improved typography */
  body {
    background-color: var(--light-gray);
    color: #333;
    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  }

  /* Text color classes */
  .text-primary, .fw-bold.text-primary {
    color: var(--primary) !important;
  }

  .text-success {
    color: var(--success) !important;
  }

  .text-danger {
    color: var(--danger) !important;
  }

  .text-warning {
    color: var(--warning) !important;
  }

  .text-info {
    color: var(--info) !important; /* Now uses dark green */
  }

  /* Card styling */
  .card {
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  }

  /* Card header styling */
  .card-header {
    background-color: var(--primary);
    color: var(--white);
    border-bottom: none;
    border-radius: 8px 8px 0 0 !important;
    padding: 15px;
  }

  .card-header h5 {
    margin: 0;
    font-weight: 600;
    color: var(--white) !important;
  }

  /* Card border styles */
  .border-primary {
    border-color: var(--primary) !important;
    border-left: 5px solid var(--primary) !important;
  }

  .border-success {
    border-color: var(--success) !important;
    border-left: 5px solid var(--success) !important;
  }

  .border-danger {
    border-color: var(--danger) !important;
    border-left: 5px solid var(--danger) !important;
  }

  .border-warning {
    border-color: var(--warning) !important;
    border-left: 5px solid var(--warning) !important;
  }

  /* Button styling */
  .btn-check:checked + .btn-outline-primary {
    background-color: var(--primary);
    border-color: var(--primary);
    color: var(--white);
  }

  .btn-outline-primary {
    color: var(--primary);
    border-color: var(--primary);
  }

  .btn-outline-primary:hover {
    background-color: var(--primary-light);
    border-color: var(--primary-light);
    color: var(--white);
  }

  /* List item styling */
  .list-group-item {
    border-left: none;
    border-right: none;
    transition: background-color 0.2s ease;
  }

  .list-group-item:hover {
    background-color: var(--accent);
  }

  /* Icons styling */
  .bi {
    opacity: 0.9;
  }

  /* Card animations */
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .stat-card {
    animation: fadeIn 0.5s ease forwards;
    animation-delay: calc(var(--i) * 0.1s);
    opacity: 0;
  }

  /* Timeline connector style for horizontal line indicator */
  .timeline-connector {
    position: relative;
    height: 2px;
    background-color: var(--primary);
    margin: 30px 0;
  }

  /* Timeline dots for visualization */
  .timeline-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: var(--primary);
    position: absolute;
    top: -5px;
  }
</style>

<div class="container-fluid mt-4">
  <!-- Top Row: Greeting and Filter -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h3 class="fw-bold text-primary mb-1">Welcome back, {{ $user->name }}!</h3>
      <p class="text-muted mb-0">Here's an overview of recent student inquiries.</p>
    </div>
    <!-- Time filter buttons with calendar icons -->
    <div class="btn-group shadow-sm" role="group" aria-label="Filter Time">
      @php
        $filters = ['day' => 'Today', 'week' => 'This Week', 'month' => 'This Month', 'all' => 'All Time'];
      @endphp
      @foreach ($filters as $val => $label)
        <input type="radio" class="btn-check" name="timeFilter" id="{{ $val }}" value="{{ $val }}" autocomplete="off" {{ $filter == $val ? 'checked' : '' }}>
        <label class="btn btn-outline-primary" for="{{ $val }}">
          @if ($val == 'day')
            <i class="bi bi-calendar-day me-1"></i>
          @elseif ($val == 'week')
            <i class="bi bi-calendar-week me-1"></i>
          @elseif ($val == 'month')
            <i class="bi bi-calendar-month me-1"></i>
          @else
            <i class="bi bi-infinity me-1"></i>
          @endif
          {{ $label }}
        </label>
      @endforeach
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row g-4 mb-4" style="margin-top: -10px;">
    <!-- Total Messages Card -->
    <div class="col-sm-6 col-xl-3" style="--i: 1">
      <div class="card border-primary rounded-3 mb-3 stat-card">
        <div class="card-body d-flex align-items-center p-3">
          <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
            <i class="bi bi-chat-left-dots-fill fs-2 text-primary"></i>
          </div>
          <div>
            <h5 class="mb-0 text-primary fw-bold">{{ $totalMessages }}</h5>
            <small class="text-muted">Total Messages</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Answered Messages Card -->
    <div class="col-sm-6 col-xl-3" style="--i: 2">
      <div class="card border-success rounded-3 mb-3 stat-card">
        <div class="card-body d-flex align-items-center p-3">
          <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
            <i class="bi bi-check-circle-fill fs-2 text-success"></i>
          </div>
          <div>
            <h5 class="mb-0 text-success fw-bold">{{ $answeredMessages }}</h5>
            <small class="text-muted">Answered Messages</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Total Users Card -->
    <div class="col-sm-6 col-xl-3" style="--i: 3">
      <div class="card border-danger rounded-3 mb-3 stat-card">
        <div class="card-body d-flex align-items-center p-3">
          <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
            <i class="bi bi-people-fill fs-2 text-danger"></i>
          </div>
          <div>
            <h5 class="mb-0 text-danger fw-bold">{{ $totalUsers }}</h5>
            <small class="text-muted">Total Users</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Most Frequent Category Card -->
    <div class="col-sm-6 col-xl-3" style="--i: 4">
      <div class="card border-warning rounded-3 mb-3 stat-card">
        <div class="card-body d-flex align-items-center p-3">
          <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
            <i class="bi bi-tags-fill fs-2 text-warning"></i>
          </div>
          <div>
            <h5 class="mb-0 text-warning fw-bold">{{ $mostCategoryName }}</h5>
            <small class="text-muted">Most Frequent Category</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- <!-- Horizontal Timeline Indicator -->
  <div class="timeline-connector">
    <div class="timeline-dot" style="left: 0%"></div>
    <div class="timeline-dot" style="left: 20%"></div>
    <div class="timeline-dot" style="left: 40%"></div>
    <div class="timeline-dot" style="left: 60%"></div>
    <div class="timeline-dot" style="left: 80%"></div>
    <div class="timeline-dot" style="left: 100%"></div>
  </div> --}}

  <!-- Charts Section -->
  <div class="row g-4 mb-4">
    <!-- Pie Chart for Category Distribution -->
    <div class="col-md-6">
      <div class="card shadow rounded-3 h-100 mb-3">
        <div class="card-header d-flex align-items-center">
          <!-- Added icon to chart header -->
          <i class="bi bi-pie-chart-fill text-white me-2"></i>
          <h5 class="mb-0 fw-bold">Inquiry Category Distribution</h5>
        </div>
        <div class="card-body p-4">
          <h6 class="text-muted text-center mb-3">Based on Category</h6>
          <div style="height: 350px; position: relative;">
            <canvas id="categoryPieChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Line Chart for Inquiry Trends -->
    <div class="col-md-6">
      <div class="card shadow rounded-3 h-100 mb-3">
        <div class="card-header d-flex align-items-center">
          <!-- Added icon to chart header -->
          <i class="bi bi-graph-up text-white me-2"></i>
          <h5 class="mb-0 fw-bold">Inquiry Trend</h5>
        </div>
        <div class="card-body p-4">
          <h6 class="text-muted text-center mb-3">Number of Inquiries</h6>
          <div style="height: 350px; position: relative;">
            <canvas id="queriesChart"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Recent Logs Section -->
  <div class="card shadow rounded-3 mb-4" style="margin-top:20px;">
    <div class="card-header d-flex justify-content-between align-items-center">
      <!-- Added icon to logs header -->
      <div class="d-flex align-items-center">
        <i class="bi bi-list-ul text-white me-2"></i>
        <h5 class="mb-0 fw-bold">Recent System Logs</h5>
      </div>

    </div>
    <div class="card-body p-0">
      <ul class="list-group list-group-flush">
        @forelse($recentLogs as $log)
          <li class="list-group-item d-flex justify-content-between align-items-start p-3">
            <div class="me-auto">
              <div class="d-flex align-items-center">
                @php
                  /* Dynamic icons based on log action type */
                  $icon = 'info-circle-fill';
                  $iconClass = 'text-info';

                  if (strpos($log->action_type, 'create') !== false) {
                    $icon = 'plus-circle-fill';
                    $iconClass = 'text-success';
                  } elseif (strpos($log->action_type, 'update') !== false) {
                    $icon = 'pencil-fill';
                    $iconClass = 'text-primary';
                  } elseif (strpos($log->action_type, 'delete') !== false) {
                    $icon = 'trash-fill';
                    $iconClass = 'text-danger';
                  } elseif (strpos($log->action_type, 'login') !== false) {
                    $icon = 'box-arrow-in-right';
                    $iconClass = 'text-primary';
                  } elseif (strpos($log->action_type, 'logout') !== false) {
                    $icon = 'box-arrow-right';
                    $iconClass = 'text-warning';
                  }
                @endphp
                <i class="bi bi-{{ $icon }} {{ $iconClass }} me-2"></i>
                <div class="fw-semibold">{{ $log->action_type }}</div>
              </div>
              <small class="text-secondary">By: {{ $log->user->name ?? 'System' }}</small>
            </div>
            <small class="text-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('F j, Y, g:i A') }}</small>
          </li>
        @empty
          <!-- Improved empty state with icon -->
          <li class="list-group-item text-center text-muted p-4">
            <i class="bi bi-inbox-fill fs-1 d-block mb-3 text-primary opacity-50"></i>
            <p>No recent logs available.</p>
          </li>
        @endforelse
      </ul>
    </div>


<script>

  
/* Custom chart colors to match the dark green theme */
const chartColors = {
  primary: '#165a36',      // Dark green
  primaryLight: '#2a7d4f', // Lighter green
  white: '#ffffff',        // White
  backgroundColor: 'rgba(22, 90, 54, 0.1)', // Transparent green
  borderColor: '#165a36'   // Dark green
};

/* Custom chart palette for pie sections - all green variants */
const categoryPalette = [
  'rgba(22, 90, 54, 0.85)',    // Dark green (primary)
  'rgba(42, 125, 79, 0.8)',    // Lighter green
  'rgba(67, 160, 71, 0.75)',   // Success green
  'rgba(76, 175, 80, 0.7)',    // Material green
  'rgba(102, 187, 106, 0.65)', // Lighter material green
  'rgba(129, 199, 132, 0.6)'   // Even lighter green
];

/* Register plugin to handle empty data states */
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
      ctx.font = '18px Poppins, sans-serif'; // Using Poppins font
      ctx.fillStyle = '#666';
      ctx.fillText('No data available for this time period', width / 2, height / 2);
      ctx.restore();
    }
  }
});

document.addEventListener("DOMContentLoaded", function () {
  /* Initialize stat cards animation */
  document.querySelectorAll('.stat-card').forEach(card => {
    card.classList.add('show');
  });

  /* Filter change event handler */
  document.querySelectorAll('input[name="timeFilter"]').forEach(radio => {
    radio.addEventListener('change', function () {
      window.location.href = "{{ route('admin.dashboard') }}?filter=" + this.value;
    });
  });

  /* Set up trend chart (line chart) */
  const filter = "{{ $filter }}";
  const isAllTime = filter === 'all';
  const chartType = 'line'; // Line chart for trend visualization

  const ctx = document.getElementById('queriesChart').getContext('2d');
  new Chart(ctx, {
    type: chartType,
    data: {
      labels: @json($labels),
      datasets: [{
        label: 'Inquiries',
        data: @json($counts),
        backgroundColor: 'rgba(22, 90, 54, 0.2)', // Transparent green
        borderColor: chartColors.primary,
        borderWidth: 3,
        fill: true,
        tension: 0.4, // Smooth line for better visualization
        pointRadius: 4,
        pointHoverRadius: 7,
        pointBackgroundColor: chartColors.white,
        pointBorderColor: chartColors.primary,
        pointBorderWidth: 2,
        pointHoverBackgroundColor: chartColors.primary,
        pointHoverBorderColor: chartColors.white
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: {
        duration: 2000,
        easing: 'easeOutQuart'
      },
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(22, 90, 54, 0.8)', // Semi-transparent green
          titleColor: '#fff',
          bodyColor: '#fff',
          bodyFont: {
            size: 14,
            family: "Poppins" // Using Poppins font
          },
          padding: 10,
          cornerRadius: 6,
          displayColors: false
        },
        noDataPlugin: {} // Activate the no data plugin
      },
      scales: {
        x: {
          ticks: {
            maxRotation: 45,
            minRotation: 30,
            autoSkip: true,
            maxTicksLimit: 10,
            font: {
              size: 12,
              family: "Poppins" // Using Poppins font
            }
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
            font: {
              size: 14,
              weight: 'bold',
              family: "Poppins" // Using Poppins font
            },
            color: chartColors.primary
          },
          ticks: {
            precision: 0,
            font: {
              size: 12,
              family: "Poppins" // Using Poppins font
            }
          },
          grid: {
            color: 'rgba(0,0,0,0.05)',
            borderDash: [5, 5]
          }
        }
      }
    }
  });

  /* Set up category distribution chart (doughnut) */
  new Chart(document.getElementById('categoryPieChart'), {
    type: 'doughnut',
    data: {
      labels: @json($categoryLabels),
      datasets: [{
        label: 'Categories',
        data: @json($categoryCounts),
        backgroundColor: categoryPalette, // Green color palette
        borderColor: chartColors.white,
        borderWidth: 2,
        hoverOffset: 10
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '65%', // Doughnut hole size
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            padding: 20,
            usePointStyle: true,
            pointStyle: 'circle',
            font: {
              family: "Poppins" // Using Poppins font
            }
          }
        },
        tooltip: {
          backgroundColor: 'rgba(22, 90, 54, 0.8)', // Semi-transparent green
          titleColor: '#fff',
          bodyColor: '#fff',
          bodyFont: {
            size: 14,
            family: "Poppins" // Using Poppins font
          },
          padding: 10,
          cornerRadius: 6
        },
        noDataPlugin: {} // Activate the no data plugin
      },
      animation: {
        animateScale: true,
        animateRotate: true
      }
    }
  });

  /* Animate cards on scroll */
  const animateOnScroll = () => {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
      const cardPosition = card.getBoundingClientRect().top;
      const screenPosition = window.innerHeight / 1.2;

      if (cardPosition < screenPosition) {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
      }
    });
  };

  window.addEventListener('scroll', animateOnScroll);
  animateOnScroll(); // Run once on page load
});
</script>
@endsection
