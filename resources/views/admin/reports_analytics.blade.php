@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Make sure Bootstrap JS is loaded -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Include Google Fonts - Poppins -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Include Custom CSS -->
<link rel="stylesheet" href="{{ asset('admin/reports_analytics.css') }}">

@php
  $user = Auth::user();
@endphp

<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Reports and Analytics</h1>
  </div>

  {{-- Filter Options --}}
  <div class="card mb-4 filter-section">
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
                'day' => 'Today',
                'week' => 'This Week',
                'month' => 'This Month',
                'custom' => $customLabel,
                'all' => 'All Time',
            ];
          @endphp

          <div class="form-group">
            <label for="filterSelect" class="form-label">Time Range</label>
            <select id="filterSelect" class="form-select" name="filter">
              @foreach ($filterOptions as $val => $label)
                <option value="{{ $val }}" {{ $filter == $val ? 'selected' : '' }}>{{ $label }}</option>
              @endforeach
            </select>
            <span id="customDateLabel" class="ms-2 text-muted" style="font-size: 0.9rem;">
              @if($filter === 'custom' && $startDate && $endDate)
                ({{ $startDate }} to {{ $endDate }})
              @endif
            </span>
          </div>

          <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-download"></i> Export Data
            </button>
            <ul class="dropdown-menu" aria-labelledby="exportDropdown">
              <li>
                <a class="dropdown-item" href="{{ route('admin.reports_analytics.exports', request()->all()) }}">
                  <i class="bi bi-file-earmark-pdf-fill text-danger"></i> Export as PDF
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="{{ route('admin.reports_export_csv', request()->all()) }}">
                  <i class="bi bi-file-earmark-spreadsheet-fill text-success"></i> Export as CSV
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
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-funnel-fill me-2"></i>Apply Filter
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Key Metrics Overview -->
  <div class="row">
    <!-- Summary Statistics Bar Chart -->
    <div class="col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header">
          <h5><i class="bi bi-bar-chart-fill me-2"></i>Summary Statistics</h5>
        </div>
        <div class="card-body" style="height: 350px;">
          <canvas id="summaryStatsChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Category Counts Pie Chart -->
    <div class="col-md-6 mb-4">
      <div class="card h-100">
        <div class="card-header">
          <h5><i class="bi bi-pie-chart-fill me-2"></i>Inquiry Category Distribution</h5>
        </div>
        <div class="card-body" style="height: 350px;">
          <canvas id="categoryCountsChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Inquiry Trend Chart -->
  <div class="card mb-4">
    <div class="card-header">
      <h5><i class="bi bi-graph-up me-2"></i>Inquiry Trend Over Time</h5>
    </div>
    <div class="card-body chart-scroll-container" style="height: 400px;">
      <div id="queriesChartWrapper" style="height: 300px;">
        <div id="chartLoading" class="d-none">
          <div class="spinner-border" role="status"></div>
          <p class="mt-2">Loading chart data...</p>
        </div>
        <canvas id="queriesChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Peak Time Analysis -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header">
          <h5><i class="bi bi-clock me-2"></i>Peak Message Hour</h5>
        </div>
        <div class="card-body" style="height: 300px;">
          <canvas id="peakHourChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header">
          <h5><i class="bi bi-calendar-week me-2"></i>Peak Message Day</h5>
        </div>
        <div class="card-body" style="height: 300px;">
          <canvas id="peakDayChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Year & Course Analysis -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header">
          <h5><i class="bi bi-person-badge me-2"></i>Most Active Year</h5>
        </div>
        <div class="card-body" style="height: 300px;">
          <canvas id="yearDistributionChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header">
          <h5><i class="bi bi-book me-2"></i>Dominant Course</h5>
        </div>
        <div class="card-body" style="height: 300px;">
          <canvas id="dominantCourseChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Inquiries by Year Level & Course -->
  <div class="row mb-4">
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header">
          <h5><i class="bi bi-bar-chart-steps me-2"></i>Top Year Levels by Inquiries</h5>
        </div>
        <div class="card-body" style="height: 300px;">
          <canvas id="yearChart"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100">
        <div class="card-header">
          <h5><i class="bi bi-diagram-3 me-2"></i>Top Courses by Inquiries</h5>
        </div>
        <div class="card-body" style="height: 300px;">
          <canvas id="courseChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Messages Per User -->
  <div class="card mb-4">
    <div class="card-header">
      <h5><i class="bi bi-people-fill me-2"></i>Messages Per User</h5>
    </div>
    <div class="card-body chart-scroll-container" style="height: 300px;">
      <canvas id="messagesPerUserChart"></canvas>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Register no data plugin for charts
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
      ctx.font = '16px Poppins, sans-serif';
      ctx.fillStyle = '#666';
      ctx.fillText('No data available for this time period', width / 2, height / 2);
      ctx.restore();
    }
  }
});

// Chart colors from CSS variables
const getChartColors = () => {
  const root = document.documentElement;
  const getVar = (name) => getComputedStyle(root).getPropertyValue(name).trim();

  return {
    primary: getVar('--chart-color-1') || '#12823e',
    secondary: getVar('--chart-color-2') || '#26A69A',
    tertiary: getVar('--chart-color-3') || '#4caf50',
    quaternary: getVar('--chart-color-4') || '#8bc34a',
    quinary: getVar('--chart-color-5') || '#cddc39',
    senary: getVar('--chart-color-6') || '#dce775',
    white: getVar('--white') || '#ffffff',
    success: getVar('--success') || '#43a047',
    danger: getVar('--danger') || '#e53935',
    warning: getVar('--warning') || '#ff9800',
  };
};

const yearCtx = document.getElementById('yearChart').getContext('2d');
const courseCtx = document.getElementById('courseChart').getContext('2d');

document.addEventListener('DOMContentLoaded', function () {
  const colors = getChartColors();

  // Set up filter functionality
  const filterSelect = document.getElementById('filterSelect');
  const customInputs = document.getElementById('customInputs');
  const applyFilterWrapper = document.getElementById('applyFilterWrapper');

  // Show/hide custom date inputs based on filter selection
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

  // Handle form submission for custom dates
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

  // Show loading animation for the main trend chart
  const chartLoading = document.getElementById('chartLoading');
  const queriesChart = document.getElementById('queriesChart');
  chartLoading.classList.remove('d-none');
  queriesChart.style.display = 'none';

  setTimeout(() => {
    chartLoading.classList.add('d-none');
    queriesChart.style.display = 'block';

    // Inquiry Trend Bar Chart with enhanced styling
    new Chart(queriesChart, {
      type: 'bar',
      data: {
        labels: @json($labels),
        datasets: [{
          label: 'Inquiries',
          data: @json($counts),
          backgroundColor: colors.primary + '99', // Semitransparent primary
          borderColor: colors.primary,
          borderWidth: 1,
          borderRadius: 4,
          hoverBackgroundColor: colors.primary,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 1000, easing: 'easeOutQuart' },
        plugins: {
          legend: { display: false },
          title: {
            display: true,
            text: 'Inquiry Distribution Over Time',
            font: {
              size: 14,
              weight: 'bold',
              family: 'Poppins'
            },
            padding: {
              top: 10,
              bottom: 20
            }
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.7)',
            titleFont: {
              family: 'Poppins',
              size: 13
            },
            bodyFont: {
              family: 'Poppins',
              size: 12
            },
            padding: 10,
            cornerRadius: 4,
            displayColors: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision: 0,
              font: {
                family: 'Poppins',
                size: 11
              }
            },
            grid: {
              color: 'rgba(0, 0, 0, 0.05)'
            }
          },
          x: {
            ticks: {
              font: {
                family: 'Poppins',
                size: 11
              },
              maxRotation: 45,
              minRotation: 30
            },
            grid: {
              display: false
            }
          }
        }
      }
    });
  }, 500);

  // Summary Statistics Chart with enhanced styling
  new Chart(document.getElementById('summaryStatsChart'), {
    type: 'bar',
    data: {
      labels: [
        'Total',
        'Answered',
        'Unanswered',
        '{{ $mostCategoryName }} (Popular Category)'
      ],
      datasets: [{
        label: 'Message Statistics',
        data: [
          {{ $totalMessages }},
          {{ $answeredMessages }},
          {{ $unAnsweredMessages }},
          {{ $mostCategoryCount }}
        ],
        backgroundColor: [
          colors.primary + 'CC',  // Primary with transparency
          colors.success + 'CC',   // Success with transparency
          colors.danger + 'CC',   // Danger with transparency
          colors.warning + 'CC'    // Warning with transparency
        ],
        borderColor: [
          colors.primary,
          colors.success,
          colors.danger,
          colors.warning
        ],
        borderWidth: 1,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      layout: {
        padding: {
          top: 20,
          bottom: 20,
          left: 20,
          right: 20
        }
      },
      scales: {
        x: {
          ticks: {
            font: {
              family: 'Poppins',
              size: 11
            }
          },
          grid: {
            display: false
          }
        },
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0,
            font: {
              family: 'Poppins',
              size: 11
            }
          },
          grid: {
            color: 'rgba(0, 0, 0, 0.05)'
          },
          title: {
            display: true,
            text: 'Number of Messages',
            font: {
              family: 'Poppins',
              size: 12,
              weight: 'bold'
            }
          }
        }
      },
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return `${context.label}: ${context.raw}`;
            }
          },
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          titleFont: {
            family: 'Poppins',
            size: 13
          },
          bodyFont: {
            family: 'Poppins',
            size: 12
          },
          padding: 10,
          cornerRadius: 4
        }
      }
    }
  });

  // Year Chart (Pie) with enhanced styling
  const yearChart = new Chart(yearCtx, {
    type: 'pie',
    data: {
      labels: @json($topYearLabels),
      datasets: [{
        label: 'Inquiries',
        data: @json($topYearCounts),
        backgroundColor: [
          colors.primary,
          colors.secondary,
          colors.tertiary,
          colors.quaternary,
          colors.quinary
        ],
        borderColor: colors.white,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            boxWidth: 12,
            padding: 15,
            font: {
              family: 'Poppins',
              size: 11
            }
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.raw || 0;
              return `${label}: ${value} inquiries`;
            }
          },
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          titleFont: {
            family: 'Poppins',
            size: 13
          },
          bodyFont: {
            family: 'Poppins',
            size: 12
          },
          padding: 10,
          cornerRadius: 4
        }
      }
    }
  });

  // Course Chart (Pie) with enhanced styling
  const courseChart = new Chart(courseCtx, {
    type: 'pie',
    data: {
      labels: @json($topCourseLabels),
      datasets: [{
        label: 'Inquiries',
        data: @json($topCourseCounts),
        backgroundColor: [
          colors.secondary,
          colors.tertiary,
          colors.quaternary,
          colors.quinary,
          colors.senary
        ],
        borderColor: colors.white,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'top',
          labels: {
            boxWidth: 12,
            padding: 15,
            font: {
              family: 'Poppins',
              size: 11
            }
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.raw || 0;
              return `${label}: ${value} inquiries`;
            }
          },
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          titleFont: {
            family: 'Poppins',
            size: 13
          },
          bodyFont: {
            family: 'Poppins',
            size: 12
          },
          padding: 10,
          cornerRadius: 4
        }
      }
    }
  });

  // Peak Hour Chart with enhanced styling
  new Chart(document.getElementById('peakHourChart'), {
    type: 'line',
    data: {
      labels: @json($peakHour->pluck('hour')),
      datasets: [{
        label: 'Messages',
        data: @json($peakHour->pluck('count')),
        backgroundColor: colors.tertiary + '40',
        borderColor: colors.tertiary,
        borderWidth: 2,
        pointRadius: 4,
        pointBackgroundColor: colors.white,
        pointBorderColor: colors.tertiary,
        pointBorderWidth: 2,
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0,
            font: {
              family: 'Poppins',
              size: 11
            }
          },
          grid: {
            color: 'rgba(0, 0, 0, 0.05)'
          },
          title: {
            display: true,
            text: 'Number of Messages',
            font: {
              family: 'Poppins',
              size: 12,
              weight: 'bold'
            }
          }
        },
        x: {
          ticks: {
            font: {
              family: 'Poppins',
              size: 11
            }
          },
          grid: {
            display: false
          },
          title: {
            display: true,
            text: 'Hour of Day',
            font: {
              family: 'Poppins',
              size: 12,
              weight: 'bold'
            }
          }
        }
      },
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            title: context => `Hour: ${context[0].label}`,
            label: context => `${context.parsed.y} messages`
          },
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          titleFont: {
            family: 'Poppins',
            size: 13
          },
          bodyFont: {
            family: 'Poppins',
            size: 12
          },
          padding: 10,
          cornerRadius: 4,
          displayColors: false
        }
      }
    }
  });

  // Peak Day Chart with enhanced styling
  new Chart(document.getElementById('peakDayChart'), {
    type: 'line',
    data: {
      labels: @json($peakDay->pluck('day')),
      datasets: [{
        label: 'Messages',
        data: @json($peakDay->pluck('count')),
        backgroundColor: colors.quaternary + '40',
        borderColor: colors.quaternary,
        borderWidth: 2,
        pointRadius: 4,
        pointBackgroundColor: colors.white,
        pointBorderColor: colors.quaternary,
        pointBorderWidth: 2,
        tension: 0.3,
        fill: true
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0,
            font: {
              family: 'Poppins',
              size: 11
            }
          },
          grid: {
            color: 'rgba(0, 0, 0, 0.05)'
          },
          title: {
            display: true,
            text: 'Number of Messages',
            font: {
              family: 'Poppins',
              size: 12,
              weight: 'bold'
            }
          }
        },
        x: {
          ticks: {
            font: {
              family: 'Poppins',
              size: 11
            }
          },
          grid: {
            display: false
          },
          title: {
            display: true,
            text: 'Day of Week',
            font: {
              family: 'Poppins',
              size: 12,
              weight: 'bold'
            }
          }
        }
      },
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            title: context => `Day: ${context[0].label}`,
            label: context => `${context.parsed.y} messages`
          },
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          titleFont: {
            family: 'Poppins',
            size: 13
          },
          bodyFont: {
            family: 'Poppins',
            size: 12
          },
          padding: 10,
          cornerRadius: 4,
          displayColors: false
        }
      }
    }
  });

  // Most Active Year Chart with enhanced styling
  new Chart(document.getElementById('yearDistributionChart'), {
    type: 'pie',
    data: {
      labels: @json($topYearLevel->pluck('year_level')),
      datasets: [{
        data: @json($topYearLevel->pluck('count')),
        backgroundColor: [
          colors.primary,
          colors.secondary,
          colors.tertiary,
          colors.quaternary
        ],
        borderColor: colors.white,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'bottom',
          labels: {
            boxWidth: 12,
            padding: 15,
            font: {
              family: 'Poppins',
              size: 11
            }
          }
        },
        title: {
          display: true,
          text: 'User Distribution by Year Level',
          font: {
            family: 'Poppins',
            size: 14,
            weight: 'bold'
          },
          padding: {
            top: 10,
            bottom: 15
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.raw || 0;
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = Math.round((value / total) * 100);
              return `${label}: ${value} users (${percentage}%)`;
            }
          },
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          titleFont: {
            family: 'Poppins',
            size: 13
          },
          bodyFont: {
            family: 'Poppins',
            size: 12
          },
          padding: 10,
          cornerRadius: 4
        }
      }
    }
  });

  // Messages per User Chart with enhanced styling
  new Chart(document.getElementById('messagesPerUserChart'), {
    type: 'bar',
    data: {
      labels: @json($messagesPerUser->pluck('userID')),
      datasets: [{
        label: 'Messages',
        data: @json($messagesPerUser->pluck('count')),
        backgroundColor: colors.primary + '99',
        borderColor: colors.primary,
        borderWidth: 1,
        borderRadius: 4,
        hoverBackgroundColor: colors.primary
      }]
    },
    options: {
      indexAxis: 'x',
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          grid: {
            display: false
          },
          ticks: {
            font: {
              family: 'Poppins',
              size: 11
            }
          }
        },
        y: {
          beginAtZero: true,
          ticks: {
            precision: 0,
            font: {
              family: 'Poppins',
              size: 11
            }
          },
          grid: {
            color: 'rgba(0, 0, 0, 0.05)'
          },
          title: {
            display: true,
            text: 'Number of Messages',
            font: {
              family: 'Poppins',
              size: 12,
              weight: 'bold'
            }
          }
        }
      },
      plugins: {
        legend: { display: false },
        title: {
          display: true,
          text: 'Message Count per User',
          font: {
            family: 'Poppins',
            size: 14,
            weight: 'bold'
          },
          padding: {
            top: 10,
            bottom: 15
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return `User ${context.label}: ${context.raw} messages`;
            }
          },
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          titleFont: {
            family: 'Poppins',
            size: 13
          },
          bodyFont: {
            family: 'Poppins',
            size: 12
          },
          padding: 10,
          cornerRadius: 4,
          displayColors: false
        }
      }
    }
  });

  // Dominant Course Chart with enhanced styling
  new Chart(document.getElementById('dominantCourseChart'), {
    type: 'pie',
    data: {
      labels: @json($topCourse->pluck('course_name')),
      datasets: [{
        data: @json($topCourse->pluck('count')),
        backgroundColor: [
          colors.secondary,
          colors.tertiary,
          colors.quaternary,
          colors.quinary,
          colors.primary
        ],
        borderColor: colors.white,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: true,
          position: 'bottom',
          labels: {
            boxWidth: 12,
            padding: 15,
            font: {
              family: 'Poppins',
              size: 11
            }
          }
        },
        title: {
          display: true,
          text: 'User Distribution by Course',
          font: {
            family: 'Poppins',
            size: 14,
            weight: 'bold'
          },
          padding: {
            top: 10,
            bottom: 15
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.raw || 0;
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = Math.round((value / total) * 100);
              return `${label}: ${value} users (${percentage}%)`;
            }
          },
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          titleFont: {
            family: 'Poppins',
            size: 13
          },
          bodyFont: {
            family: 'Poppins',
            size: 12
          },
          padding: 10,
          cornerRadius: 4
        }
      }
    }
  });

  // Category Pie Chart with enhanced styling
  new Chart(document.getElementById('categoryCountsChart'), {
    type: 'doughnut',
    data: {
      labels: ['Admission', 'Scholarship', 'Placement', 'General'],
      datasets: [{
        data: [{{ $admissionMessages }}, {{ $scholarshipMessages }}, {{ $placementMessages }}, {{ $generalMessages }}],
        backgroundColor: [
          colors.primary + 'DD',
          colors.tertiary + 'DD',
          colors.quaternary + 'DD',
          colors.quinary + 'DD'
        ],
        borderColor: colors.white,
        borderWidth: 2,
        hoverOffset: 10
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '60%',
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            boxWidth: 15,
            padding: 15,
            font: {
              family: 'Poppins',
              size: 11
            }
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              const label = context.label || '';
              const value = context.raw || 0;
              const total = context.dataset.data.reduce((a, b) => a + b, 0);
              const percentage = Math.round((value / total) * 100);
              return `${label}: ${value} messages (${percentage}%)`;
            }
          },
          backgroundColor: 'rgba(0, 0, 0, 0.7)',
          titleFont: {
            family: 'Poppins',
            size: 13
          },
          bodyFont: {
            family: 'Poppins',
            size: 12
          },
          padding: 10,
          cornerRadius: 4
        }
      },
      animation: {
        animateScale: true,
        animateRotate: true
      }
    }
  });
});
</script>

<!-- AJAX Script for Real-time Data Updates -->
<script>
$(document).ready(function() {
  const filter = '{{ $filter }}'; // Get current filter

  // Update charts with fresh data every 5 minutes if user is viewing dashboard
  const autoRefresh = () => {
    if (document.hidden) return; // Skip refresh if tab is not visible

    $.ajax({
      url: '/reports/ajax-data',
      type: 'GET',
      data: { filter: filter },
      success: function(response) {
        console.log('Auto-refreshed data received');
        // Data received successfully
        // You can implement chart updates here if needed
      },
      error: function(xhr) {
        console.error('Error fetching report data:', xhr);
      }
    });
  };

  // Set up auto-refresh timer (300000 ms = 5 minutes)
  // Uncomment this if you want auto-refresh functionality
  // const refreshTimer = setInterval(autoRefresh, 300000);

  // Stop auto-refresh when user leaves the page
  // $(window).on('beforeunload', function() {
  //   clearInterval(refreshTimer);
  // });
});
</script>
@endsection
