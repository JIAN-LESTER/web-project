@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('admin/logs.css') }}">
<div class="container-fluid logs-container">
    <div class="logs-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="logs-title">User Activity Logs</h1>
                <p class="logs-subtitle">Track user interactions and system events</p>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="action-buttons">
                    <button id="exportCSV" class="btn btn-outline-secondary">
                        <i class="fas fa-download me-2"></i>Export CSV
                    </button>
                    <button id="refreshLogs" class="btn btn-outline-secondary">
                        <i class="fas fa-sync-alt me-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="logs-card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-lg-8 col-md-7">
                    <form method="GET" action="{{ route('admin.logs') }}" class="logs-search-form">
                        <div class="search-wrapper">
                            <input type="text" name="search" class="form-control search-input"
                                   placeholder="Search by user, action, or message ID..."
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn search-button">Search</button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 col-md-5">
                    <div class="dropdown filter-dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-2"></i>Filter Options
                        </button>
                        <ul class="dropdown-menu filter-menu" aria-labelledby="filterDropdown">
                            <li>
                                <div class="filter-section">
                                    <h6 class="filter-heading">Filter by Action</h6>
                                    <div class="form-check">
                                        <input class="form-check-input filter-check" type="checkbox" value="Login" id="loginCheck">
                                        <label class="form-check-label" for="loginCheck">Login</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input filter-check" type="checkbox" value="Email" id="emailCheck">
                                        <label class="form-check-label" for="emailCheck">Email Verification</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input filter-check" type="checkbox" value="Register" id="registerCheck">
                                        <label class="form-check-label" for="registerCheck">Registration</label>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="filter-section">
                                    <h6 class="filter-heading">Date Range</h6>
                                    <div class="date-filters">
                                        <div class="date-group">
                                            <label for="startDate">From</label>
                                            <input type="date" id="startDate" class="form-control">
                                        </div>
                                        <div class="date-group">
                                            <label for="endDate">To</label>
                                            <input type="date" id="endDate" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="filter-actions">
                                    <button type="button" class="btn" id="applyFilters">Apply Filters</button>
                                    <button type="button" class="btn" id="clearFilters">Clear</button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="logs-table-wrapper">
                <table class="table logs-table">
                    <thead>
                        <tr>
                            <th class="sortable col-id" data-sort="id">
                                <div class="sort-header">
                                    Log ID <i class="fas fa-sort"></i>
                                </div>
                            </th>
                            <th class="sortable col-user" data-sort="user">
                                <div class="sort-header">
                                    User <i class="fas fa-sort"></i>
                                </div>
                            </th>
                            <th class="sortable col-message" data-sort="messageId">
                                <div class="sort-header">
                                    Message ID <i class="fas fa-sort"></i>
                                </div>
                            </th>
                            <th class="sortable col-action" data-sort="action">
                                <div class="sort-header">
                                    Action <i class="fas fa-sort"></i>
                                </div>
                            </th>
                            <th class="sortable col-timestamp" data-sort="timestamp">
                                <div class="sort-header">
                                    Timestamp <i class="fas fa-sort"></i>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="log-row" data-log-id="{{ $log->logID }}">
                                <td class="log-id">{{ $log->logID }}</td>
                                <td class="log-user">
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            {{ substr($log->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="user-details">
                                            <div class="user-name">{{ $log->user->name ?? 'Unknown User' }}</div>
                                            <div class="user-email">{{ $log->user->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="log-message">
                                    @if ($log->messageID == null)
                                        <span class="na-badge">N/A</span>
                                    @else
                                        <span class="message-badge">{{ $log->messageID }}</span>
                                    @endif
                                </td>
                                <td class="log-action">
                                    @php
                                        $actionClass = '';

                                        if (stripos($log->action_type, 'login') !== false) {
                                            $actionClass = 'action-login';
                                        } elseif (stripos($log->action_type, 'verified') !== false) {
                                            $actionClass = 'action-verified';
                                        } elseif (stripos($log->action_type, 'register') !== false || stripos($log->action_type, 'account') !== false) {
                                            $actionClass = 'action-register';
                                        } elseif (stripos($log->action_type, 'attempt') !== false) {
                                            $actionClass = 'action-attempt';
                                        } else {
                                            $actionClass = 'action-other';
                                        }
                                    @endphp

                                    <span class="action-badge {{ $actionClass }}">
                                        {{ $log->action_type }}
                                    </span>
                                </td>
                                <td class="log-timestamp">
                                    <div class="timestamp-group">
                                        <div class="log-date">
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->timestamp)
                                                ->subHours(8)
                                                ->timezone('Asia/Manila')
                                                ->format('F j, Y') }}
                                        </div>
                                        <div class="log-time">
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->timestamp)
                                                ->subHours(8)
                                                ->timezone('Asia/Manila')
                                                ->format('h:i A') }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="no-data">
                                    <div class="empty-logs">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="empty-icon">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                        <h5>No logs found</h5>
                                        <p>No matching logs with the current search criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrapper">
                {{ $logs->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<!-- Log Details Panel -->
<div class="log-details-panel" id="logDetailsPanel">
    <div class="log-details-header">
        <h5 class="log-details-title">Log Details</h5>
        <button type="button" class="log-details-close" id="closeLogDetails">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="log-details-content">
        <div class="log-details-section">
            <h6 class="log-details-section-title">Basic Information</h6>
            <div class="log-detail-item">
                <span class="detail-label">Log ID:</span>
                <span class="detail-value highlight" id="panel-log-id"></span>
            </div>
            <div class="log-detail-item">
                <span class="detail-label">User:</span>
                <span class="detail-value" id="panel-user"></span>
            </div>
            <div class="log-detail-item">
                <span class="detail-label">Email:</span>
                <span class="detail-value" id="panel-email"></span>
            </div>
            <div class="log-detail-item">
                <span class="detail-label">Message ID:</span>
                <span class="detail-value" id="panel-message-id"></span>
            </div>
        </div>

        <div class="log-details-section">
            <h6 class="log-details-section-title">Activity Details</h6>
            <div class="log-detail-item">
                <span class="detail-label">Action:</span>
                <span class="detail-value highlight" id="panel-action"></span>
            </div>
            <div class="log-detail-item">
                <span class="detail-label">Date & Time:</span>
                <span class="detail-value" id="panel-timestamp"></span>
            </div>
        </div>

        <div class="log-details-section">
            <h6 class="log-details-section-title">Technical Details</h6>
            <div class="log-detail-item">
                <span class="detail-label">IP Address:</span>
                <div class="detail-value">
                    <div class="ip-address-info">
                        <div class="info-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div class="info-details">
                            <span class="info-main" id="panel-ip">192.168.1.1</span>
                            <span class="info-secondary">Local Network</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="log-detail-item">
                <span class="detail-label">Browser:</span>
                <div class="detail-value">
                    <div class="browser-info">
                        <div class="info-icon">
                            <i class="fab fa-chrome"></i>
                        </div>
                        <div class="info-details">
                            <span class="info-main" id="panel-browser">Chrome</span>
                            <span class="info-secondary" id="panel-os">Windows</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="log-details-footer">
        <button type="button" class="btn btn-secondary" id="panelCloseBtn">Close</button>
    </div>
</div>
<div class="log-details-backdrop" id="logDetailsBackdrop"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Make table rows clickable to show details
    const logRows = document.querySelectorAll('.log-row');
    const logDetailsPanel = document.getElementById('logDetailsPanel');
    const logDetailsBackdrop = document.getElementById('logDetailsBackdrop');

    logRows.forEach(row => {
        row.addEventListener('click', function() {
            const logId = this.getAttribute('data-log-id');
            const userName = this.querySelector('.user-name')?.textContent || 'Unknown';
            const userEmail = this.querySelector('.user-email')?.textContent || '';
            const messageId = this.querySelector('.log-message')?.textContent.trim() || 'N/A';
            const action = this.querySelector('.action-badge')?.textContent.trim() || '';
            const date = this.querySelector('.log-date')?.textContent || '';
            const time = this.querySelector('.log-time')?.textContent || '';

            // Set panel content
            document.getElementById('panel-log-id').textContent = logId;
            document.getElementById('panel-user').textContent = userName;
            document.getElementById('panel-email').textContent = userEmail;
            document.getElementById('panel-message-id').textContent = messageId;
            document.getElementById('panel-action').textContent = action;
            document.getElementById('panel-timestamp').textContent = `${date} at ${time}`;

            // Set browser info with separate OS
            const browserInfo = getBrowserInfo();
            document.getElementById('panel-browser').textContent = browserInfo.browser;
            document.getElementById('panel-os').textContent = browserInfo.os;

            // Set IP info - in a real app, this would come from the server
            document.getElementById('panel-ip').textContent = '192.168.1.1';

            // Set appropriate icon for browser
            const browserIcon = document.querySelector('.browser-info .info-icon i');
            if (browserInfo.browser.toLowerCase().includes('chrome')) {
                browserIcon.className = 'fab fa-chrome';
            } else if (browserInfo.browser.toLowerCase().includes('firefox')) {
                browserIcon.className = 'fab fa-firefox';
            } else if (browserInfo.browser.toLowerCase().includes('edge')) {
                browserIcon.className = 'fab fa-edge';
            } else if (browserInfo.browser.toLowerCase().includes('safari')) {
                browserIcon.className = 'fab fa-safari';
            } else {
                browserIcon.className = 'fas fa-globe';
            }

            // Show panel and backdrop with animation
            logDetailsPanel.classList.add('show');
            logDetailsBackdrop.classList.add('show');
        });
    });

    // Function to detect browser and OS
    function getBrowserInfo() {
        const userAgent = navigator.userAgent;
        let browser = "Unknown";
        let os = "Unknown";

        // Detect browser
        if (userAgent.indexOf("Firefox") > -1) {
            browser = "Firefox";
        } else if (userAgent.indexOf("Chrome") > -1) {
            browser = "Chrome";
        } else if (userAgent.indexOf("Safari") > -1) {
            browser = "Safari";
        } else if (userAgent.indexOf("Edge") > -1) {
            browser = "Edge";
        } else if (userAgent.indexOf("MSIE") > -1 || userAgent.indexOf("Trident") > -1) {
            browser = "Internet Explorer";
        }

        // Detect OS
        if (userAgent.indexOf("Win") > -1) {
            os = "Windows";
        } else if (userAgent.indexOf("Mac") > -1) {
            os = "MacOS";
        } else if (userAgent.indexOf("Linux") > -1) {
            os = "Linux";
        } else if (userAgent.indexOf("Android") > -1) {
            os = "Android";
        } else if (userAgent.indexOf("iOS") > -1 || userAgent.indexOf("iPhone") > -1 || userAgent.indexOf("iPad") > -1) {
            os = "iOS";
        }

        return { browser, os };
    }

    // Close panel functionality
    const closePanel = function() {
        logDetailsPanel.classList.remove('show');
        logDetailsBackdrop.classList.remove('show');
    };

    // Add multiple ways to close the panel
    document.getElementById('closeLogDetails').addEventListener('click', closePanel);
    document.getElementById('panelCloseBtn').addEventListener('click', closePanel);
    logDetailsBackdrop.addEventListener('click', closePanel);

    // Add keyboard escape to close panel
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && logDetailsPanel.classList.contains('show')) {
            closePanel();
        }
    });

    // Add loading spinner functionality
    document.getElementById('refreshLogs').addEventListener('click', function() {
        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Refreshing...';
        this.disabled = true;
        setTimeout(() => {
            window.location.reload();
        }, 800);
    });

    // Add CSV export functionality
    document.getElementById('exportCSV').addEventListener('click', function() {
        // Show loading state
        const originalText = this.innerHTML;
        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Exporting...';
        this.disabled = true;

        // Get current applied filters to pass to the export endpoint
        const filterBoxes = document.querySelectorAll('.filter-check:checked');
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const searchTerm = document.querySelector('.search-input').value;

        // Build query parameters
        let params = new URLSearchParams();
        if (searchTerm) params.append('search', searchTerm);
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);

        // Add selected actions
        filterBoxes.forEach(box => {
            params.append('actions[]', box.value);
        });

        // Make a request to the export endpoint
        fetch(`/admin/logs/export?${params.toString()}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/csv'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.blob();
        })
        .then(blob => {
            // Create a download link and trigger it
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;

            // Get current date for filename
            const date = new Date();
            const formattedDate = date.getFullYear() + '-' +
                                ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
                                ('0' + date.getDate()).slice(-2);

            a.download = `user_activity_logs_${formattedDate}.csv`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);

            // Reset button
            this.innerHTML = originalText;
            this.disabled = false;
        })
        .catch(error => {
            console.error('Error exporting CSV:', error);

            // Reset button
            this.innerHTML = originalText;
            this.disabled = false;

            // Show error message
            alert('An error occurred while exporting logs. Please try again.');
        });
    });

    // Add filter functionality
    document.getElementById('applyFilters').addEventListener('click', function() {
        const filterBoxes = document.querySelectorAll('.filter-check:checked');
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // Create filter pills display
        let activeFilters = [];
        filterBoxes.forEach(box => activeFilters.push(box.value));

        if (startDate) activeFilters.push(`From: ${startDate}`);
        if (endDate) activeFilters.push(`To: ${endDate}`);

        if (activeFilters.length > 0) {
            alert(`Filters applied: ${activeFilters.join(', ')}`);
            // Here you would actually apply the filters via AJAX or form submit
        }

        // Close dropdown
        const dropdownToggle = document.querySelector('.dropdown-toggle');
        const bsDropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
        if (bsDropdown) {
            bsDropdown.hide();
        }
    });

    // Clear filters
    document.getElementById('clearFilters').addEventListener('click', function() {
        document.querySelectorAll('.filter-check').forEach(el => el.checked = false);
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
    });

    // Add sorting functionality
    const sortHeaders = document.querySelectorAll('.sortable');
    sortHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const sort = this.getAttribute('data-sort');

            // Toggle sort direction
            const currentDirection = this.getAttribute('data-direction') || 'asc';
            const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

            // Reset all headers
            document.querySelectorAll('.sortable').forEach(el => {
                el.removeAttribute('data-direction');
                el.querySelector('i').className = 'fas fa-sort';
            });

            // Update this header
            this.setAttribute('data-direction', newDirection);
            this.querySelector('i').className = `fas fa-sort-${newDirection === 'asc' ? 'up' : 'down'}`;

            // Here you would actually perform the sorting via AJAX or form submit
            // For demo purposes we'll just show an alert
            alert(`Sorting by ${sort} (${newDirection})`);
        });
    });
});
</script>
@endsection