@extends('layouts.app')

@section('content')
    <!-- Include Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Include Custom CSS -->
    <link rel="stylesheet" href="{{ asset('admin/logs.css') }}">

    <div class="container-fluid page-container">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="page-title">User Activity Logs</h1>
                    <p class="page-subtitle">Track user interactions and system events</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="action-buttons">
                        <button id="refreshLogs" class="btn primary-btn">
                            <i class="fas fa-sync-alt"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <form method="GET" action="{{ route('admin.logs') }}" class="search-form" id="searchForm">
                            <div class="row g-2">
                                <div class="col-lg-8 col-md-7">
                                    <input type="text" name="search" class="form-control" id="liveSearch"
                                        placeholder="Search by user, action, or date..." value="{{ request('search') }}">
                                </div>
                                <div class="col-lg-4 col-md-5">
                                    <select name="filter" class="form-control category-filter" id="filterDropdown">
                                        <option value="all" {{ request('filter', 'all') == 'all' ? 'selected' : '' }}>All Activities</option>
                                        <option value="user" {{ request('filter') == 'user' ? 'selected' : '' }}>User Actions</option>
                                        <option value="action" {{ request('filter') == 'action' ? 'selected' : '' }}>System Actions</option>
                                        <option value="login" {{ request('filter') == 'login' ? 'selected' : '' }}>Login Activities</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table class="table data-table">
                        <thead>
                            <tr>
                                <th class="sortable col-id" data-sort="id">
                                    <div class="sort-header">
                                        ID <i class="fas fa-sort"></i>
                                    </div>
                                </th>
                                <th class="sortable col-user" data-sort="user">
                                    <div class="sort-header">
                                        User <i class="fas fa-sort"></i>
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
                                <tr class="data-row" data-log-id="{{ $log->logID }}" style="--i: {{ $loop->index }}">
                                    <td class="log-id-cell">
                                        <span class="log-id-badge">{{ $log->logID }}</span>
                                    </td>
                                    <td class="log-user-cell">
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
                                    <td class="log-action-cell">
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
                                        <span class="badge {{ $actionClass }}">
                                            {{ $log->action_type }}
                                        </span>
                                    </td>
                                    <td class="log-timestamp-cell">
                                        <div class="timestamp-group">
                                            <div class="log-date">
                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->created_at)
                                                    ->subHours(8)
                                                    ->timezone('Asia/Manila')
                                                    ->format('M j, Y') }}
                                            </div>
                                            <div class="log-time">
                                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $log->created_at)
                                                    ->subHours(8)
                                                    ->timezone('Asia/Manila')
                                                    ->format('h:i A') }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="no-data">
                                        <div class="empty-state">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="empty-icon">
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
                    <div class="pagination-info">
                        Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} results
                    </div>
                    <div class="pagination">
                        <!-- Left arrow -->
                        <a href="{{ $logs->previousPageUrl() }}"
                            class="page-link {{ $logs->onFirstPage() ? 'disabled' : '' }}">
                            <i class="fas fa-chevron-left"></i>
                        </a>

                        <!-- Page numbers -->
                        @for ($i = 1; $i <= $logs->lastPage(); $i++)
                            <a href="{{ $logs->url($i) }}" class="page-link {{ $logs->currentPage() == $i ? 'active' : '' }}">
                                {{ $i }}
                            </a>
                        @endfor

                        <!-- Right arrow -->
                        <a href="{{ $logs->nextPageUrl() }}"
                            class="page-link {{ !$logs->hasMorePages() ? 'disabled' : '' }}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Log Details Panel -->
    <div class="details-panel" id="logDetailsPanel">
        <div class="details-header">
            <h5 class="details-title">Log Details</h5>
            <button type="button" class="details-close" id="closeLogDetails">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="details-content">
            <div class="details-section">
                <h6 class="details-section-title">Basic Information</h6>
                <div class="detail-item">
                    <span class="detail-label">Log ID:</span>
                    <span class="detail-value highlight" id="panel-log-id"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">User:</span>
                    <span class="detail-value" id="panel-user"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value" id="panel-email"></span>
                </div>
            </div>

            <div class="details-section">
                <h6 class="details-section-title">Activity Details</h6>
                <div class="detail-item">
                    <span class="detail-label">Action:</span>
                    <span class="detail-value highlight" id="panel-action"></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Date & Time:</span>
                    <span class="detail-value" id="panel-timestamp"></span>
                </div>
            </div>
        </div>
        <div class="details-footer">
            <button type="button" class="btn secondary-btn" id="panelCloseBtn">Close</button>
        </div>
    </div>
    <div class="details-backdrop" id="logDetailsBackdrop"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Make table rows clickable to show details
            const logRows = document.querySelectorAll('.data-row');
            const logDetailsPanel = document.getElementById('logDetailsPanel');
            const logDetailsBackdrop = document.getElementById('logDetailsBackdrop');

            logRows.forEach(row => {
                row.addEventListener('click', function () {
                    const logId = this.getAttribute('data-log-id');
                    const userName = this.querySelector('.user-name')?.textContent || 'Unknown';
                    const userEmail = this.querySelector('.user-email')?.textContent || '';
                    const action = this.querySelector('.badge')?.textContent.trim() || '';
                    const date = this.querySelector('.log-date')?.textContent || '';
                    const time = this.querySelector('.log-time')?.textContent || '';

                    // Set panel content
                    document.getElementById('panel-log-id').textContent = logId;
                    document.getElementById('panel-user').textContent = userName;
                    document.getElementById('panel-email').textContent = userEmail;
                    document.getElementById('panel-action').textContent = action;
                    document.getElementById('panel-timestamp').textContent = `${date} at ${time}`;

                    // Show panel and backdrop
                    logDetailsPanel.classList.add('show');
                    logDetailsBackdrop.classList.add('show');
                });
            });

            // Close panel functionality
            const closePanel = function () {
                logDetailsPanel.classList.remove('show');
                logDetailsBackdrop.classList.remove('show');
            };

            // Add multiple ways to close the panel
            document.getElementById('closeLogDetails').addEventListener('click', closePanel);
            document.getElementById('panelCloseBtn').addEventListener('click', closePanel);
            logDetailsBackdrop.addEventListener('click', closePanel);

            // Add keyboard escape to close panel
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && logDetailsPanel.classList.contains('show')) {
                    closePanel();
                }
            });

            // Add loading spinner functionality for refresh button
            document.getElementById('refreshLogs').addEventListener('click', function () {
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Refreshing...';
                this.disabled = true;
                setTimeout(() => {
                    window.location.reload();
                }, 800);
            });

            // Filter functionality (simplified for dropdown)
            const filterDropdown = document.getElementById('filterDropdown');
            if (filterDropdown) {
                filterDropdown.addEventListener('change', function () {
                    const currentUrl = new URL(window.location.href);

                    // Clear existing filter param
                    currentUrl.searchParams.delete('filter');

                    // Add selected filter if not 'all'
                    if (this.value && this.value !== 'all') {
                        currentUrl.searchParams.set('filter', this.value);
                    }

                    window.location.href = currentUrl.toString();
                });
            }

            // Add live search functionality
            const liveSearch = document.getElementById('liveSearch');
            if (liveSearch) {
                liveSearch.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('.data-row');

                    rows.forEach(row => {
                        const userName = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
                        const userEmail = row.querySelector('.user-email')?.textContent.toLowerCase() || '';
                        const logId = row.querySelector('.log-id-badge')?.textContent.toLowerCase() || '';
                        const action = row.querySelector('.badge')?.textContent.toLowerCase() || '';

                        // Check if any field contains the search term
                        const isMatch = userName.includes(searchTerm) ||
                            userEmail.includes(searchTerm) ||
                            logId.includes(searchTerm) ||
                            action.includes(searchTerm);

                        // Show/hide row based on match
                        row.style.display = isMatch ? '' : 'none';
                    });

                    // Check if no results found
                    const visibleRows = document.querySelectorAll('.data-row[style="display: none;"]');
                    const tableBody = document.querySelector('.data-table tbody');
                    const noResultsRow = document.getElementById('noSearchResults');

                    if (visibleRows.length === rows.length && searchTerm !== '') {
                        // No matches found
                        if (!noResultsRow) {
                            const newRow = document.createElement('tr');
                            newRow.id = 'noSearchResults';
                            newRow.innerHTML = `
                                <td colspan="4" class="no-data">
                                    <div class="empty-state">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="empty-icon">
                                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                            <line x1="12" y1="9" x2="12" y2="13"></line>
                                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                        </svg>
                                        <h5>No matching logs</h5>
                                        <p>Try a different search term</p>
                                    </div>
                                </td>
                            `;
                            tableBody.appendChild(newRow);
                        }
                    } else if (noResultsRow) {
                        // Remove no results message if there are matches
                        noResultsRow.remove();
                    }
                });

                // Prevent form submission on enter key
                liveSearch.form.addEventListener('submit', function (e) {
                    // Only prevent default if it's the live search form being submitted by pressing Enter
                    if (e.submitter === null || e.submitter === undefined) {
                        e.preventDefault();
                    }
                });
            }

            // Add sorting functionality
            const sortHeaders = document.querySelectorAll('.sortable');
            sortHeaders.forEach(header => {
                header.addEventListener('click', function () {
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

                    // Add actual sorting logic (you can implement AJAX or form submit here)
                    const currentUrl = new URL(window.location.href);
                    currentUrl.searchParams.set('sort', sort);
                    currentUrl.searchParams.set('direction', newDirection);
                    window.location.href = currentUrl.toString();
                });
            });
        });
    </script>

@endsection
