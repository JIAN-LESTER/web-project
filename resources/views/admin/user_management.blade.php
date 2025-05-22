@extends('layouts.app')

@section('content')
    <!-- Include Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Include Custom CSS -->
    <link rel="stylesheet" href="{{ asset('admin/user_management.css') }}">





    <style>
        .filter-dropdown {
            position: relative;
            z-index: 1050;
            /* Bootstrap default dropdown z-index */
        }

        /* Prevent clipping from table wrapper */
        .logs-table-wrapper {
            position: relative;
            overflow: visible !important;
            /* override any overflow:hidden */
            z-index: 1;
        }

        .dropdown-menu {
            background-color: white !important;
            color: #212529 !important;
        }
    </style>

    <div class="container-fluid users-container">
        <div class="users-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="users-title">Users Management</h1>
                    <p class="users-subtitle">Manage accounts and user roles</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="action-buttons">
                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                            <i class="fas fa-user-plus"></i> Add New User
                        </button>
                    </div>
                </div>
            </div>
        </div>

      

        <div class="users-card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <form method="GET" action="{{ route('admin.user_management') }}" class="users-search-form"
                            id="searchForm">
                            <div class="search-wrapper">
                                <input type="text" name="search" class="form-control search-input" id="liveSearch"
                                    placeholder="Search by name, email or role..." value="{{ $search }}">
                                <button type="submit" class="search-button">Search</button>
                                
                            </div>

                            <div class="col-lg-4">
                        <div class="dropdown filter-dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter Options
                            </button>

                            <ul class="dropdown-menu p-3 shadow-sm bg-white border rounded-3" style="min-width: 300px;"
                                aria-labelledby="filterDropdown">
                                <!-- Filter by Role -->
                                <li class="mb-3">
                                    <h6 class="text-primary mb-2">Filter by Role</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="admin" id="adminCheck">
                                        <label class="form-check-label" for="adminCheck">Admin</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="user" id="userCheck">
                                        <label class="form-check-label" for="userCheck">User</label>
                                    </div>
                                </li>

                                <!-- Filter by Status -->
                                <li class="mb-3">
                                    <h6 class="text-primary mb-2">Filter by Status</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="active" id="activeCheck">
                                        <label class="form-check-label" for="activeCheck">Active</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="inactive" id="inactiveCheck">
                                        <label class="form-check-label" for="inactiveCheck">Inactive</label>
                                    </div>
                                </li>

                                <!-- Filter by Year/Course -->
                                <li class="mb-3">
                                    <h6 class="text-primary mb-2">Filter by Year/Course</h6>
                                    <div class="mb-2">
                                        <label for="yearSelect" class="form-label">Year</label>
                                        <select class="form-select form-select-sm" id="yearSelect" name="year">
                                            <option value="">Select Year</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year->year_level }}">{{ $year->year_level }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Filter by Course -->
                                    <div>
                                        <label for="courseSelect" class="form-label">Course</label>
                                        <select class="form-select form-select-sm" id="courseSelect" name="course">
                                            <option value="">Select Course</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->course_name }}">{{ $course->course_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>

                                <!-- Actions -->
                                <li class="d-flex justify-content-between mt-2">
                                    <button type="button" class="btn btn-sm btn-primary" id="applyFilters">Apply
                                        Filters</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                        id="clearFilters">Clear</button>
                                </li>
                            </ul>
                        </div>
                        </form>
                    </div>
                    
                    </div>
                </div>

                <div class="users-table-wrapper">
                    <table class="table users-table">
                        <thead>
                            <tr>
                                <th class="sortable col-avatar" data-sort="avatar">
                                    <div class="sort-header">
                                        <i class="fas fa-sort"></i>
                                    </div>
                                </th>
                                <th class="sortable col-user" data-sort="user">
                                    <div class="sort-header">
                                        User <i class="fas fa-sort"></i>
                                    </div>
                                </th>
                                <th class="sortable col-role" data-sort="role">
                                    <div class="sort-header">
                                        Role <i class="fas fa-sort"></i>
                                    </div>
                                </th>
                                <th class="sortable col-course" data-sort="course">
                                    <div class="sort-header">
                                        Course <i class="fas fa-sort"></i>
                                    </div>
                                </th>
                                <th class="sortable col-year" data-sort="year">
                                    <div class="sort-header">
                                        Year <i class="fas fa-sort"></i>
                                    </div>
                                </th>
                                <th class="sortable col-status" data-sort="status">
                                    <div class="sort-header">
                                        Status <i class="fas fa-sort"></i>
                                    </div>
                                </th>
                                <th class="col-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="user-row" data-user-id="{{ $user->userID }}" style="--i: {{ $loop->index }}">
                                    <td class="user-avatar-cell">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/' . $user->avatar) }}" class="user-avatar"
                                                alt="{{ $user->name }}">
                                        @else
                                            <div class="user-avatar">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="user-info-cell">
                                        <div class="user-info">
                                            <div class="user-details">
                                                <div class="user-name">{{ $user->name }}</div>
                                                <div class="user-email">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="user-role-cell">
                                        @if ($user->role == 'admin')
                                            <span class="role-badge role-admin">
                                                Admin
                                            </span>
                                        @else
                                            <span class="role-badge role-user">
                                                User
                                            </span>
                                        @endif
                                    </td>
                                    <td class="user-course-cell">{{ $user->course->course_name ?? 'N/A' }}</td>
                                    <td class="user-year-cell">{{ $user->year->year_level ?? 'N/A' }}</td>
                                    <td class="user-status-cell">
                                        @if($user->user_status == 'active')
                                            <span class="status-badge status-active">
                                                <i class="fas fa-circle status-icon"></i> Active
                                            </span>
                                        @else
                                            <span class="status-badge status-inactive">
                                                <i class="fas fa-circle status-icon"></i> Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="user-actions-cell">
                                        <div class="action-buttons">
                                            <button type="button" class="btn-icon" data-bs-toggle="modal"
                                                data-bs-target="#editUserModal" title="Edit User"
                                                onclick="populateEditModal({{ $user->userID }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn-icon" onclick="confirmDelete({{ $user->userID }})"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Delete User">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        <form id="delete-form-{{ $user->userID }}"
                                            action="{{ route('admin.destroy', $user->userID) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="no-data">
                                        <div class="empty-users">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" class="empty-icon">
                                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="9" cy="7" r="4"></circle>
                                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                            </svg>
                                            <h5>No users found</h5>
                                            <p>No matching users with the current search criteria</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                        users
                    </div>
                    <div class="pagination">
                        <!-- Left arrow -->
                        <a href="{{ $users->previousPageUrl() }}"
                            class="page-link {{ $users->onFirstPage() ? 'disabled' : '' }}">
                            <i class="fas fa-chevron-left"></i>
                        </a>

                        <!-- Page numbers -->
                        @for ($i = 1; $i <= $users->lastPage(); $i++)
                            <a href="{{ $users->url($i) }}" class="page-link {{ $users->currentPage() == $i ? 'active' : '' }}">
                                {{ $i }}
                            </a>
                        @endfor

                        <!-- Right arrow -->
                        <a href="{{ $users->nextPageUrl() }}"
                            class="page-link {{ !$users->hasMorePages() ? 'disabled' : '' }}">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Details Panel -->
    <div class="user-details-panel" id="userDetailsPanel">
        <div class="user-details-header">
            <h5 class="user-details-title">User Details</h5>
            <button type="button" class="user-details-close" id="closeUserDetails">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="user-details-content">
            <div class="user-details-section">
                <h6 class="user-details-section-title">Basic Information</h6>
                <div class="user-detail-item">
                    <span class="detail-label">User ID:</span>
                    <span class="detail-value highlight" id="panel-user-id"></span>
                </div>
                <div class="user-detail-item">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value" id="panel-user-name"></span>
                </div>
                <div class="user-detail-item">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value" id="panel-user-email"></span>
                </div>
            </div>

            <div class="user-details-section">
                <h6 class="user-details-section-title">Account Details</h6>
                <div class="user-detail-item">
                    <span class="detail-label">Role:</span>
                    <span class="detail-value highlight" id="panel-user-role"></span>
                </div>
                <div class="user-detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value highlight" id="panel-user-status"></span>
                </div>
                <div class="user-detail-item">
                    <span class="detail-label">Course:</span>
                    <span class="detail-value" id="panel-user-course"></span>
                </div>
                <div class="user-detail-item">
                    <span class="detail-label">Year Level:</span>
                    <span class="detail-value" id="panel-user-year"></span>
                </div>
            </div>
        </div>
        <div class="user-details-footer">
            <a href="#" class="btn btn-edit" id="panel-edit-btn">Edit User</a>
            <button type="button" class="btn btn-secondary" id="panelCloseBtn">Close</button>
        </div>
    </div>
    <div class="user-details-backdrop" id="userDetailsBackdrop"></div>

      <!-- Add New User Modal -->
      <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="role" class="form-label">Role</label>
                                    <select class="form-select" id="role" name="role">
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="avatar" class="form-label">Avatar</label>
                                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="year_id" class="form-label">Year</label>
                                    <select class="form-select" id="year_id" name="year_id">
                                        <option value="">Select Year</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year->yearID }}">{{ $year->year_level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="course_id" class="form-label">Course</label>
                                    <select class="form-select" id="course_id" name="course_id">
                                        <option value="">Select Course</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->courseID }}">{{ $course->course_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit User Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.update', $user->userID) }}" id="editUserForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="editName" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="editEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="editEmail" name="email" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editRole" class="form-label">Role</label>
                                    <select class="form-select" id="editRole" name="role">
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editAvatar" class="form-label">Avatar</label>
                                    <input type="file" class="form-control" id="editAvatar" name="avatar" accept="image/*">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editYear" class="form-label">Year</label>
                                    <select class="form-select" id="editYear" name="year_id">
                                        <option value="">Select Year</option>
                                        @foreach ($years as $year)
                                            <option value="{{ $year->yearID }}">{{ $year->year_level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="editCourse" class="form-label">Course</label>
                                    <select class="form-select" id="editCourse" name="course_id">
                                        <option value="">Select Course</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->courseID }}">{{ $course->course_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="editStatus" class="form-label">Status</label>
                                    <select class="form-select" id="editStatus" name="user_status">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const editButtons = document.querySelectorAll('.btn-icon[data-bs-toggle="tooltip"][title="Edit User"]');
                const editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
                const editUserForm = document.getElementById('editUserForm');

                editButtons.forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();

                        const userRow = this.closest('.user-row');
                        const userId = userRow.getAttribute('data-user-id');
                        const userName = userRow.querySelector('.user-name')?.textContent.trim() || '';
                        const userEmail = userRow.querySelector('.user-email')?.textContent.trim() || '';
                        const userRole = userRow.querySelector('.role-badge')?.textContent.trim() || '';
                        const userStatus = userRow.querySelector('.status-badge')?.textContent.trim() || '';
                        const userCourse = userRow.querySelector('.user-course-cell')?.getAttribute('data-course-id') || '';
                        const userYear = userRow.querySelector('.user-year-cell')?.getAttribute('data-year-id') || '';

                        // Populate modal fields
                        editUserForm.action = `/admin/users/${userId}`;
                        document.getElementById('editName').value = userName;
                        document.getElementById('editEmail').value = userEmail;
                        document.getElementById('editRole').value = userRole.toLowerCase();
                        document.getElementById('editStatus').value = userStatus.toLowerCase();
                        document.getElementById('editCourse').value = userCourse;
                        document.getElementById('editYear').value = userYear;

                        // Show modal
                        editUserModal.show();
                    });
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const yearSelect = document.getElementById('year_id');
                const courseSelect = document.getElementById('course_id');

                yearSelect.addEventListener('change', function () {
                    if (!this.value) {
                        courseSelect.value = '';
                        courseSelect.disabled = true;
                    } else {
                        courseSelect.disabled = false;
                    }
                });

                // Disable course select initially if no year is selected
                if (!yearSelect.value) {
                    courseSelect.disabled = true;
                }
            });
        </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Make table rows clickable to show details
            const userRows = document.querySelectorAll('.user-row');
            const userDetailsPanel = document.getElementById('userDetailsPanel');
            const userDetailsBackdrop = document.getElementById('userDetailsBackdrop');

            userRows.forEach(row => {
                row.addEventListener('click', function (e) {
                    // Don't show panel if clicked on action buttons
                    if (e.target.closest('.btn-action') || e.target.closest('.action-buttons')) {
                        return;
                    }

                    const userId = this.getAttribute('data-user-id');
                    const userName = this.querySelector('.user-name')?.textContent || '';
                    const userEmail = this.querySelector('.user-email')?.textContent || '';
                    const userRole = this.querySelector('.role-badge')?.textContent.trim() || '';
                    const userStatus = this.querySelector('.status-badge')?.textContent.trim() || '';
                    const userCourse = this.querySelector('.user-course-cell')?.textContent || '';
                    const userYear = this.querySelector('.user-year-cell')?.textContent || '';

                    // Set panel content
                    document.getElementById('panel-user-id').textContent = userId;
                    document.getElementById('panel-user-name').textContent = userName;
                    document.getElementById('panel-user-email').textContent = userEmail;
                    document.getElementById('panel-user-role').textContent = userRole;
                    document.getElementById('panel-user-status').textContent = userStatus;
                    document.getElementById('panel-user-course').textContent = userCourse;
                    document.getElementById('panel-user-year').textContent = userYear;

                    // Set edit button link
                    document.getElementById('panel-edit-btn').href = `/admin/users/${userId}/edit`;

                    // Show panel and backdrop
                    userDetailsPanel.classList.add('show');
                    userDetailsBackdrop.classList.add('show');
                });
            });

            // Close panel functionality
            const closePanel = function () {
                userDetailsPanel.classList.remove('show');
                userDetailsBackdrop.classList.remove('show');
            };

            // Add multiple ways to close the panel
            document.getElementById('closeUserDetails').addEventListener('click', closePanel);
            document.getElementById('panelCloseBtn').addEventListener('click', closePanel);
            userDetailsBackdrop.addEventListener('click', closePanel);

            // Add keyboard escape to close panel
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && userDetailsPanel.classList.contains('show')) {
                    closePanel();
                }
            });

            // Delete confirmation
            window.confirmDelete = function (userId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#12823e',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'swal-confirm-btn',
                        cancelButton: 'swal-cancel-btn'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${userId}`).submit();
                    }
                });
            };

            // Filter functionality
            document.getElementById('applyFilters').addEventListener('click', function () {
                const roleFilters = [];
                if (document.getElementById('adminCheck').checked) roleFilters.push('admin');
                if (document.getElementById('userCheck').checked) roleFilters.push('user');

                const statusFilters = [];
                if (document.getElementById('activeCheck').checked) statusFilters.push('active');
                if (document.getElementById('inactiveCheck').checked) statusFilters.push('inactive');

                const currentUrl = new URL(window.location.href);

                // Clear existing role and status params
                currentUrl.searchParams.delete('role');
                currentUrl.searchParams.delete('status');

                // Add selected filters
                roleFilters.forEach(role => {
                    currentUrl.searchParams.append('role', role);
                });

                statusFilters.forEach(status => {
                    currentUrl.searchParams.append('status', status);
                });

                window.location.href = currentUrl.toString();
            });

            document.getElementById('clearFilters').addEventListener('click', function () {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('role');
                currentUrl.searchParams.delete('status');
                window.location.href = currentUrl.toString();
            });

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

            // Add live search functionality
            const liveSearch = document.getElementById('liveSearch');
            if (liveSearch) {
                liveSearch.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const rows = document.querySelectorAll('.user-row');

                    rows.forEach(row => {
                        const userName = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
                        const userEmail = row.querySelector('.user-email')?.textContent.toLowerCase() || '';
                        const userRole = row.querySelector('.role-badge')?.textContent.toLowerCase() || '';
                        const userCourse = row.querySelector('.user-course-cell')?.textContent.toLowerCase() || '';
                        const userYear = row.querySelector('.user-year-cell')?.textContent.toLowerCase() || '';

                        // Check if any field contains the search term
                        const isMatch = userName.includes(searchTerm) ||
                            userEmail.includes(searchTerm) ||
                            userRole.includes(searchTerm) ||
                            userCourse.includes(searchTerm) ||
                            userYear.includes(searchTerm);

                        // Show/hide row based on match
                        row.style.display = isMatch ? '' : 'none';
                    });

                    // Check if no results found
                    const visibleRows = document.querySelectorAll('.user-row[style="display: none;"]');
                    const tableBody = document.querySelector('.users-table tbody');
                    const noResultsRow = document.getElementById('noSearchResults');

                    if (visibleRows.length === rows.length && searchTerm !== '') {
                        // No matches found
                        if (!noResultsRow) {
                            const newRow = document.createElement('tr');
                            newRow.id = 'noSearchResults';
                            newRow.innerHTML = `
                            <td colspan="7" class="no-data">
                                <div class="empty-users">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="empty-icon">
                                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                        <line x1="12" y1="9" x2="12" y2="13"></line>
                                        <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                    </svg>
                                    <h5>No matching users</h5>
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
        });
    </script>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#12823e',
                    customClass: {
                        container: 'my-swal-container',
                        confirmButton: 'swal-confirm-btn'
                    },
                    didOpen: () => {
                        document.querySelector('.swal2-container').style.zIndex = '10000000';
                    }
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',
                    title: 'Something went wrong',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#12823e',
                    customClass: {
                        container: 'my-swal-container',
                        confirmButton: 'swal-confirm-btn'
                    },
                    didOpen: () => {
                        document.querySelector('.swal2-container').style.zIndex = '10000000';
                    }
                });
            });
        </script>
    @endif
@endsection