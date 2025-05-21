@extends('layouts.app')
@section('content')
<!-- Include required libraries for charts and icons -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<!-- Google Fonts added for better typography -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>

  :root {
    /* Preserve original stat card colors */
    --primary: #12823e;      /* Dark green as primary color */
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
    --blue: #007bff;          /* Added blue color for total messages card */

    /* New minimalist colors for headers and charts */
    --header-bg: #f8f9fa;     /* Light gray for headers */
    --header-text: #343a40;   /* Dark text for headers */
    --border-color: #dee2e6;  /* Light border color */

    /* Chart colors - more diverse palette */
    --chart-color-1: #5C6BC0; /* Indigo */
    --chart-color-2: #26A69A; /* Teal */
    --chart-color-3: #EC407A; /* Pink */
    --chart-color-4: #7E57C2; /* Purple */
    --chart-color-5: #42A5F5; /* Blue */
    --chart-color-6: #66BB6A; /* Green */
  }

  /* Hide scrollbars but allow scrolling */
  ::-webkit-scrollbar {
    width: 0;
    height: 0;
    background: transparent;
    display: none;
  }

  /* Hide scrollbars for IE, Edge, and Firefox */
  * {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
  }

  /* Base styling with improved typography */
  body {
    background-color: var(--light-gray);
    color: #333;
    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    font-size: 13px;
  }

  /* Text color classes - maintain original for stat cards */
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
    color: var(--info) !important;
  }

  /* Main title adjustments */
  h3.fw-bold.text-primary {
    font-size: 1.5rem;
    margin-bottom: 0.2rem !important;
  }

  .text-muted {
    font-size: 0.95rem;
  }

  /* Card styling with enhanced box shadow */
  .card {
    border: none;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Increased box shadow */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    margin-bottom: 0.5rem !important;
    border-radius: 8px;
  }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); /* Enhanced hover shadow */
  }

  /* Stat cards specific styling - preserved as requested */
  .stat-card .card-body {
    padding: 1.3rem !important;
  }

  .stat-card h5 {
    font-size: 1.2rem !important;
    margin-bottom: 10px !important;
    font-weight: bold;
  }

  .stat-card small {
    font-size: 0.9rem !important;
  }

  .stat-card .rounded-circle {
    padding: 0.4rem !important;
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .stat-card .bi {
    font-size: 1.6rem !important;
  }

  /* Card header styling - minimalist approach */
  .card-header {
    background-color: var(--header-bg);
    color: var(--header-text);
    border-bottom: 1px solid var(--border-color);
    border-radius: 8px 8px 0 0 !important;
    padding: 12px 16px;
  }

  .card-header h5 {
    margin: 0;
    font-weight: 600;
    color: var(--header-text) !important;
    font-size: 0.95rem;
  }

  /* Chart headers with icons */
  .card-header .bi {
    font-size: 0.9rem;
    color: #555; /* Dark gray icons */
  }

  /* Chart cards body padding */
  .chart-card .card-body {
    padding: 1.1rem !important;
  }

  .chart-card h6 {
    font-size: 0.85rem;
    margin-bottom: 3rem !important;
  }

  /* Chart containers */
  .chart-container {
    margin-top: -30px;
    height: 250px !important;
    position: relative;
  }

  /* Card border styles - preserved for stat cards */
  .border-primary {
    border-color: var(--primary) !important;
    border-left: 3px solid var(--primary) !important;
  }

  .border-success {
    border-color: var(--success) !important;
    border-left: 3px solid var(--success) !important;
  }

  .border-danger {
    border-color: var(--danger) !important;
    border-left: 3px solid var(--danger) !important;
  }

  .border-blue {
    border-color: var(--blue) !important;
    border-left: 3px solid var(--blue) !important;
  }

  .border-warning {
    border-color: var(--warning) !important;
    border-left: 3px solid var(--warning) !important;
  }

  /* Custom dropdown styling */
  .custom-dropdown {
    position: relative;
    display: inline-block;
  }

  .custom-dropdown-toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 10px 16px;
    min-width: 150px;
    font-weight: 500;
    color: #333;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 15px;
    font-family: 'Poppins', sans-serif;
  }

  .custom-dropdown-toggle:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.12);
  }

  .custom-dropdown-toggle .bi {
    font-size: 14px;
    color: #777;
    margin-left: 10px;
  }

  .custom-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 10;
    background-color: white;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border-radius: 8px;
    margin-top: 5px;
    overflow: hidden;
    display: none;
    min-width: 150px;
    border: 1px solid #dee2e6;
  }

  .custom-dropdown-item {
    padding: 12px 16px;
    cursor: pointer;
    transition: background-color 0.2s ease;
    display: block;
    color: #333;
    font-size: 15px;
    border-bottom: 1px solid #f5f5f5;
  }

  .custom-dropdown-item:last-child {
    border-bottom: none;
  }

  .custom-dropdown-item:hover {
    background-color: #f8f9fa;
  }

  .custom-dropdown-item.active {
    background-color: #f8f9fa;
    font-weight: 500;
  }

  .show {
    display: block;
  }

  /* List item styling for logs */
  .list-group-item {
    border-left: none;
    border-right: none;
    transition: background-color 0.2s ease;
    padding: 0.35rem !important;
  }

  .list-group-item:hover {
    background-color: #efefef;
  }

  .list-group-item .fw-semibold {
    font-size: 0.85rem;
  }

  .list-group-item small {
    font-size: 0.8rem;
  }

  /* Icons styling */
  .bi {
    opacity: 0.9;
  }

  /* Log icons */
  .list-group-item .bi {
    font-size: 0.85rem;
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

  /* Container adjustments */
  .container-fluid {
    padding-left: 0.75rem;
    padding-right: 0.75rem;
    padding-bottom: 0 !important;
  }

  /* Row gap adjustments */
  .row.g-4 {
    --bs-gutter-x: 0.5rem;
    --bs-gutter-y: 0.5rem;
  }

  /* Margin adjustments */
  .mb-4 {
    margin-bottom: 0.7rem !important;
  }

  .mt-4 {
    margin-top: 0.7rem !important;
  }

  /* Extra margin reduction for the bottom section */
  .mb-2 {
    margin-bottom: 0.3rem !important;
  }

  /* System logs section specific adjustments */
  .logs-section {
    margin-bottom: 0.3rem !important;
  }

  .logs-section .card-body {
    padding-bottom: 0.3rem !important;
  }

  /* Make last card take remaining space without creating scroll */
  .logs-section .list-group-item:last-child {
    border-bottom: none !important;
  }
</style>
<div class="container-fluid mt-3">
  <!-- Header comment to match image -->
  <!-- Top Row: Greeting and Filter -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h3 class="fw-bold text-primary mb-1">Welcome back, {{ $user->name }}!</h3>
      <p class="text-muted mb-0">Here's an overview of recent student inquiries.</p>
    </div>

    <!-- Custom Dropdown Time Filter -->
    <div class="custom-dropdown">
      <div class="custom-dropdown-toggle" id="timeFilterDropdown">
        <span id="selectedFilter">
          @php
            $filterLabels = [
              'day' => 'Today',
              'week' => 'This Week',
              'month' => 'This Month',
              'all' => 'All Time'
            ];
            $currentFilter = $filterLabels[$filter] ?? 'This Month';
          @endphp
          {{ $currentFilter }}
        </span>
        <i class="bi bi-chevron-down"></i>
      </div>
      <div class="custom-dropdown-menu" id="timeFilterMenu">
        <div class="custom-dropdown-item {{ $filter == 'day' ? 'active' : '' }}" data-value="day">
          Today
        </div>
        <div class="custom-dropdown-item {{ $filter == 'week' ? 'active' : '' }}" data-value="week">
          This Week
        </div>
        <div class="custom-dropdown-item {{ $filter == 'month' ? 'active' : '' }}" data-value="month">
          This Month
        </div>
        <div class="custom-dropdown-item {{ $filter == 'all' ? 'active' : '' }}" data-value="all">
          All Time
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row g-4 mb-3" style="margin-top: -30px;">
    <!-- Total Messages Card -->
    <div class="col-sm-6 col-xl-3" style="--i: 1">
      <div class="card border-blue rounded-3 mb-2 stat-card">
        <div class="card-body d-flex align-items-center p-2">
          <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3" style="background-color: rgba(0, 123, 255, 0.1) !important;">
            <i class="bi bi-chat-left-dots-fill fs-2 text-primary" style="color: var(--blue) !important;"></i>
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
  <div class="card border-success rounded-3 mb-2 stat-card">
    <div class="card-body d-flex align-items-center p-2">
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
  <div class="card border-danger rounded-3 mb-2 stat-card">
    <div class="card-body d-flex align-items-center p-2">
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
  <div class="card border-warning rounded-3 mb-2 stat-card">
    <div class="card-body d-flex align-items-center p-2">
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
  <!-- Charts Section -->
  <div class="row g-4 mb-2" style="margin-top: -30px;">
    <!-- Pie Chart for Category Distribution -->
    <div class="col-md-6">
      <div class="card shadow rounded-3 h-100 mb-2 chart-card">
        <div class="card-header d-flex align-items-center">
          <h5 class="mb-0 fw-bold">Inquiry Category Distribution</h5>
        </div>
        <div class="card-body p-3">
          <h6 class="text-muted text-center mb-2">Based on Category</h6>
          <div class="chart-container" style="position: relative;">
            <canvas id="categoryPieChart"></canvas>
          </div>
        </div>
      </div>
    </div>
<!-- Line Chart for Inquiry Trends -->
<div class="col-md-6">
  <div class="card shadow rounded-3 h-100 mb-2 chart-card">
    <div class="card-header d-flex align-items-center">
      <h5 class="mb-0 fw-bold">Inquiry Trend</h5>
    </div>
    <div class="card-body p-3">
      <h6 class="text-muted text-center mb-2">Number of Inquiries</h6>
      <div class="chart-container">
        <canvas id="queriesChart"></canvas>
      </div>
    </div>
  </div>
</div>
  </div>
  <!-- Recent Logs Section -->
  <div class="card shadow rounded-3 mb-1 logs-section" style="margin-top:15px;">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <h5 class="mb-0 fw-bold">Recent System Logs</h5>
      </div>
    </div>
    <div class="card-body p-0">
      <ul class="list-group list-group-flush">
        @forelse($recentLogs as $log)
          <li class="list-group-item d-flex justify-content-between align-items-start p-2">
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
      <li class="list-group-item text-center text-muted p-3">
        <i class="bi bi-inbox-fill fs-1 d-block mb-2 text-primary opacity-50"></i>
        <p>No recent logs available.</p>
      </li>
    @endforelse
  </ul>
</div>
  </div>
</div>
<script>
/* New chart colors - more diverse palette */
const chartColors = {
  primary: '#5C6BC0',      // Indigo
  secondary: '#26A69A',    // Teal
  tertiary: '#EC407A',     // Pink
  quaternary: '#7E57C2',   // Purple
  quinary: '#42A5F5',      // Blue
  white: '#ffffff',        // White
  backgroundColor: 'rgba(92, 107, 192, 0.1)', // Transparent indigo
  borderColor: '#5C6BC0'   // Indigo
};

/* Multi-color chart palette for pie chart */
const categoryPalette = [
  'rgba(92, 107, 192, 0.85)',   // Indigo
  'rgba(38, 166, 154, 0.85)',   // Teal
  'rgba(236, 64, 122, 0.85)',   // Pink
  'rgba(126, 87, 194, 0.85)',   // Purple
  'rgba(66, 165, 245, 0.85)',   // Blue
  'rgba(102, 187, 106, 0.85)'   // Green
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
      ctx.font = '14px Poppins, sans-serif';
      ctx.fillStyle = '#666';
      ctx.fillText('No data available for this time period', width / 2, height / 2);
      ctx.restore();
    }
  }
});

document.addEventListener("DOMContentLoaded", function () {
  /* Hide scrollbars */
  const hideScrollbars = () => {
    document.documentElement.style.overflow = 'hidden';
    document.body.style.overflow = 'hidden';

    // Create a style element to ensure all scrollbars are hidden
    const style = document.createElement('style');
    style.textContent = `
      ::-webkit-scrollbar {
        width: 0 !important;
        height: 0 !important;
        display: none !important;
      }
      * {
        -ms-overflow-style: none !important;
        scrollbar-width: none !important;
      }
    `;
    document.head.appendChild(style);
  };

  hideScrollbars();

  /* Initialize stat cards animation */
  document.querySelectorAll('.stat-card').forEach(card => {
    card.classList.add('show');
  });

  /* Custom dropdown functionality */
  const dropdown = document.getElementById('timeFilterDropdown');
  const menu = document.getElementById('timeFilterMenu');
  const selectedText = document.getElementById('selectedFilter');
  const items = document.querySelectorAll('.custom-dropdown-item');

  // Toggle dropdown menu
  dropdown.addEventListener('click', function() {
    menu.classList.toggle('show');
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', function(event) {
    if (!dropdown.contains(event.target)) {
      menu.classList.remove('show');
    }
  });

  // Handle dropdown item selection
  items.forEach(item => {
    item.addEventListener('click', function() {
      const value = this.getAttribute('data-value');
      const text = this.textContent.trim();

      // Update selected text
      selectedText.textContent = text;

      // Remove active class from all items
      items.forEach(i => i.classList.remove('active'));

      // Add active class to selected item
      this.classList.add('active');

      // Hide dropdown
      menu.classList.remove('show');

      // Navigate to the new filter
      window.location.href = "{{ route('admin.dashboard') }}?filter=" + value;
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
        backgroundColor: 'rgba(66, 165, 245, 0.2)', // Light blue background
        borderColor: '#42A5F5', // Blue line
        borderWidth: 2, // Thinner line
        fill: true,
        tension: 0.4, // Smooth line for better visualization
        pointRadius: 3, // Smaller points
        pointHoverRadius: 5,
        pointBackgroundColor: chartColors.white,
        pointBorderColor: '#42A5F5', // Blue border
        pointBorderWidth: 2,
        pointHoverBackgroundColor: '#42A5F5', // Blue on hover
        pointHoverBorderColor: chartColors.white
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      animation: {
        duration: 1500, // Faster animation
        easing: 'easeOutQuart'
      },
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          backgroundColor: 'rgba(66, 165, 245, 0.8)', // Semi-transparent blue
          titleColor: '#fff',
          bodyColor: '#fff',
          bodyFont: {
            size: 12,
            family: "Poppins" // Using Poppins font
          },
          padding: 8,
          cornerRadius: 4,
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
            maxTicksLimit: 8,
            font: {
              size: 10,
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
              size: 11,
              weight: 'bold',
              family: "Poppins" // Using Poppins font
            },
            color: '#555555' // Dark gray text
          },
          ticks: {
            precision: 0,
            font: {
              size: 10,
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
        backgroundColor: categoryPalette, // Multi-color palette
        borderColor: chartColors.white,
        borderWidth: 1, // Thinner border
        hoverOffset: 8
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
            padding: 15,
            usePointStyle: true,
            pointStyle: 'circle',
            font: {
              size: 11,
              family: "Poppins" // Using Poppins font
            }
          }
        },
        tooltip: {
          backgroundColor: 'rgba(52, 58, 64, 0.8)', // Semi-transparent dark gray
          titleColor: '#fff',
          bodyColor: '#fff',
          bodyFont: {
            size: 12,
            family: "Poppins" // Using Poppins font
          },
          padding: 8,
          cornerRadius: 4
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
