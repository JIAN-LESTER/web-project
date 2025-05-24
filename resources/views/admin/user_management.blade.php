@extends('layouts.app')

@section('content')
    <!-- Include Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Include Custom CSS -->
    <link rel="stylesheet" href="{{ asset('admin/user_management.css') }}">

    <div class="container-fluid users-container">
        <div class="users-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="users-title">Users Management</h1>
                    <p class="users-subtitle">Manage accounts and user roles</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="action-buttons">
                        <button id="openAddUserBtn" class="btn upload-document-btn">
                            <i class="fas fa-user-plus"></i>Add New User
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="users-card">
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <form method="GET" action="{{ route('admin.user_management') }}" class="users-search-form" id="searchForm">
                            <div class="row g-2">
                                <div class="col-lg-8 col-md-7">
                                    <input type="text" name="search" class="form-control" id="liveSearch"
                                        placeholder="Search by name, email or role..." value="{{ $search }}">
                                </div>
                                <div class="col-lg-4 col-md-5">
                                    <select name="category_filter" class="form-control category-filter" id="categoryFilter">
                                        <option value="">All Roles</option>
                                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                                    </select>
                                </div>
                            </div>
                        </form>
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
                                <th class="sortable col-year" data-sort="year">
                                    <div class="sort-header">
                                        Year <i class="fas fa-sort"></i>
                                    </div>
                                </th>
                                <th class="sortable col-course" data-sort="course">
                                    <div class="sort-header">
                                        Course <i class="fas fa-sort"></i>
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
                                    <td class="user-year-cell" data-year-id="{{ $user->yearID }}">
                                        {{ $user->year->year_level ?? 'N/A' }}
                                    </td>
                                    <td class="user-course-cell" data-course-id="{{ $user->courseID }}">
                                        {{ $user->course->course_name ?? 'N/A' }}
                                    </td>
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
                                            <button type="button" class="btn-icon"
                                                onclick="populateEditModal({{ $user->userID }})" title="Edit User">
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
            <a href="#" class="btn btn-primary" id="panel-edit-btn">Edit User</a>
            <button type="button" class="btn btn-secondary" id="panelCloseBtn">Close</button>
        </div>
    </div>
    <div class="user-details-backdrop" id="userDetailsBackdrop"></div>

    <!-- Edit User Modal -->
    <div class="upload-modal-backdrop" id="editUserModalBackdrop"></div>
    <div class="upload-modal" id="editUserModal">
        <div class="upload-modal-content">
            <div class="upload-modal-header">
                <h4 class="upload-modal-title">Edit User</h4>
                <button type="button" class="upload-modal-close" id="closeEditUserModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="upload-modal-body">
                <form method="POST" action="" enctype="multipart/form-data" id="editUserForm">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="editName" class="minimalist-label">
                                <i class="fas fa-user"></i> Name
                            </label>
                            <input type="text" id="editName" name="name" class="minimalist-input" required
                                placeholder="Enter user name">
                            <div class="invalid-feedback" id="edit-name-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="editEmail" class="minimalist-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="editEmail" name="email" class="minimalist-input" required
                                placeholder="Enter user email">
                            <div class="invalid-feedback" id="edit-email-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="editRole" class="minimalist-label">
                                <i class="fas fa-user-tag"></i> Role
                            </label>
                            <select name="role" id="editRole" class="minimalist-select" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            <div class="invalid-feedback" id="edit-role-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="editAvatar" class="minimalist-label">
                                <i class="fas fa-image"></i> Avatar
                            </label>
                            <input type="file" id="editAvatar" name="avatar" class="file-input" accept="image/*">
                            <div class="invalid-feedback" id="edit-avatar-error"></div>
                        </div>

                        <!-- Year Level -->
                        <div class="form-group">
                            <label for="edit-year" class="minimalist-label">
                                <i class="fas fa-graduation-cap"></i> Year Level
                            </label>
                            <select class="minimalist-select" id="edit-year" name="year_id">
                                <option value="" selected disabled>Select Year</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->yearID }}">{{ $year->year_level }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Course -->
                        <div class="form-group">
                            <label for="edit-course" class="minimalist-label">
                                <i class="fas fa-book-open"></i> Course
                            </label>
                            <select class="minimalist-select" id="edit-course" name="course_id">
                                <option value="" selected disabled>Select Course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->courseID }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editStatus" class="minimalist-label">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <select name="user_status" id="editStatus" class="minimalist-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <div class="invalid-feedback" id="edit-status-error"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="upload-modal-footer">
                <button type="button" class="btn-minimalist btn-cancel" id="cancelEditUserBtn">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="submit" form="editUserForm" class="btn-minimalist btn-upload">
                    <i class="fas fa-save"></i> Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="upload-modal-backdrop" id="addUserModalBackdrop"></div>
    <div class="upload-modal" id="addUserModal">
        <div class="upload-modal-content">
            <div class="upload-modal-header">
                <h4 class="upload-modal-title">Add New User</h4>
                <button type="button" class="upload-modal-close" id="closeAddUserModalBtn">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="upload-modal-body">
                <form method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data" id="addUserForm">
                    @csrf

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="name" class="minimalist-label">
                                <i class="fas fa-user"></i>
                                Name
                            </label>
                            <input type="text" id="name" name="name" class="minimalist-input" required
                                placeholder="Enter user name">
                            <div class="invalid-feedback" id="name-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="minimalist-label">
                                <i class="fas fa-envelope"></i>
                                Email
                            </label>
                            <input type="email" id="email" name="email" class="minimalist-input" required
                                placeholder="Enter user email">
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="password" class="minimalist-label">
                                <i class="fas fa-lock"></i>
                                Password
                            </label>
                            <input type="password" id="password" name="password" class="minimalist-input" required
                                placeholder="Enter password">
                            <div class="invalid-feedback" id="password-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="minimalist-label">
                                <i class="fas fa-lock"></i>
                                Confirm Password
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="minimalist-input" required placeholder="Confirm password">
                            <div class="invalid-feedback" id="password-confirmation-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="role" class="minimalist-label">
                                <i class="fas fa-user-tag"></i>
                                Role
                            </label>
                            <select name="role" id="role" class="minimalist-select" required>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                            <div class="invalid-feedback" id="role-error"></div>
                        </div>

                        <div class="form-group file-upload-group">
                            <label class="minimalist-label">
                                <i class="fas fa-image"></i>
                                Avatar
                            </label>
                            <div class="file-upload-area">
                                <input type="file" id="avatar" name="avatar" class="file-input" accept="image/*">
                                <div class="file-upload-display">
                                    <div class="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                        <span class="upload-text">Choose image or drag & drop</span>
                                        <span class="upload-subtitle">PNG, JPG, JPEG (max 2MB)</span>
                                    </div>
                                    <div class="file-selected" id="fileSelected" style="display: none;">
                                        <i class="fas fa-image selected-icon"></i>
                                        <div class="file-info">
                                            <span class="file-name" id="selectedFileName"></span>
                                            <span class="file-size" id="selectedFileSize"></span>
                                        </div>
                                        <button type="button" class="remove-file" id="removeFile">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="invalid-feedback" id="avatar-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="year_id" class="minimalist-label">
                                <i class="fas fa-calendar-alt"></i>
                                Year
                            </label>
                            <select name="year_id" id="year_id" class="minimalist-select">
                                <option value="">Select Year</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->yearID }}">{{ $year->year_level }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="year-error"></div>
                        </div>

                        <div class="form-group">
                            <label for="course_id" class="minimalist-label">
                                <i class="fas fa-book"></i>
                                Course
                            </label>
                            <select name="course_id" id="course_id" class="minimalist-select">
                                <option value="">Select Course</option>
                                @foreach ($courses as $course)
                                    <option value="{{ $course->courseID }}">{{ $course->course_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="course-error"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="upload-modal-footer">
                <button type="button" class="btn-minimalist btn-cancel" id="cancelAddUserBtn">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
                <button type="submit" form="addUserForm" class="btn-minimalist btn-upload">
                    <i class="fas fa-user-plus"></i>
                    Add User
                </button>
            </div>
        </div>
    </div>

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
                    confirmButtonColor: '#0F4C3A',
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

            // Filter functionality (simplified for dropdown)
            const categoryFilter = document.getElementById('categoryFilter');
            if (categoryFilter) {
                categoryFilter.addEventListener('change', function () {
                    const currentUrl = new URL(window.location.href);

                    // Clear existing role param
                    currentUrl.searchParams.delete('role');

                    // Add selected filter if not empty
                    if (this.value) {
                        currentUrl.searchParams.set('role', this.value);
                    }

                    window.location.href = currentUrl.toString();
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

        // Add User Modal functionality
        document.addEventListener('DOMContentLoaded', function () {
            // Elements for Add User Modal
            const addUserModal = document.getElementById('addUserModal');
            const addUserModalBackdrop = document.getElementById('addUserModalBackdrop');
            const closeAddUserModalBtn = document.getElementById('closeAddUserModalBtn');
            const cancelAddUserBtn = document.getElementById('cancelAddUserBtn');
            const openAddUserBtn = document.getElementById('openAddUserBtn');
            const addUserForm = document.getElementById('addUserForm');

            // File input elements
            const fileInput = document.getElementById('avatar');
            const fileUploadArea = document.querySelector('.file-upload-area');
            const uploadPlaceholder = document.querySelector('.upload-placeholder');
            const fileSelected = document.getElementById('fileSelected');
            const selectedFileName = document.getElementById('selectedFileName');
            const selectedFileSize = document.getElementById('selectedFileSize');
            const removeFileBtn = document.getElementById('removeFile');

            // Show modal
            const showModal = () => {
                addUserModal.classList.add('show');
                addUserModalBackdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            };

            // Close modal
            const closeModal = () => {
                addUserModal.classList.remove('show');
                addUserModalBackdrop.classList.remove('show');
                document.body.style.overflow = '';
                resetForm();
            };

            // Reset form and clear validation errors
            const resetForm = () => {
                addUserForm.reset();
                hideFileSelected();
                clearValidationErrors();
            };

            const clearValidationErrors = () => {
                const errorFields = addUserForm.querySelectorAll('.invalid-feedback');
                errorFields.forEach(field => field.textContent = '');
            };

            // File input functionality
            const showFileSelected = function (file) {
                const fileSize = formatFileSize(file.size);
                selectedFileName.textContent = file.name;
                selectedFileSize.textContent = fileSize;

                uploadPlaceholder.style.display = 'none';
                fileSelected.style.display = 'flex';
            };

            const hideFileSelected = function () {
                uploadPlaceholder.style.display = 'flex';
                fileSelected.style.display = 'none';
            };

            const formatFileSize = function (bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            };

            // File input events
            if (fileInput) {
                fileInput.addEventListener('change', function () {
                    if (this.files.length > 0) {
                        const file = this.files[0];
                        if (validateFile(file)) {
                            showFileSelected(file);
                        }
                    }
                });
            }

            if (removeFileBtn) {
                removeFileBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    fileInput.value = '';
                    hideFileSelected();
                });
            }

            // File validation
            const validateFile = function (file) {
                const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (!allowedTypes.includes(file.type)) {
                    showFieldError('avatar-error', 'Please select a valid image file (PNG, JPG, JPEG)');
                    return false;
                }

                if (file.size > maxSize) {
                    showFieldError('avatar-error', 'File size must be less than 2MB');
                    return false;
                }

                clearFieldError('avatar-error');
                return true;
            };

            const showFieldError = function (errorId, message) {
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.textContent = message;
                }
            };

            const clearFieldError = function (errorId) {
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.textContent = '';
                }
            };

            // Event Listeners for modal open/close
            openAddUserBtn.addEventListener('click', showModal);
            closeAddUserModalBtn.addEventListener('click', closeModal);
            cancelAddUserBtn.addEventListener('click', closeModal);
            addUserModalBackdrop.addEventListener('click', closeModal);

            // ESC key closes modal
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && addUserModal.classList.contains('show')) {
                    closeModal();
                }
            });

            // Role, Year, Course selects
            const roleSelect = document.getElementById('role');
            const yearSelect = document.getElementById('year_id');
            const courseSelect = document.getElementById('course_id');

            // Helper: Set select value to N/A (empty string or option with value="")
            function setToNA(selectElement) {
                const naOption = Array.from(selectElement.options).find(opt => opt.value === "");
                if (naOption) {
                    selectElement.value = naOption.value;
                } else {
                    selectElement.selectedIndex = 0;
                }
            }

            // Toggle year and course inputs based on role
            function toggleFieldsBasedOnRole() {
                const isAdmin = roleSelect.value === 'admin';

                if (isAdmin) {
                    setToNA(yearSelect);
                    setToNA(courseSelect);
                    yearSelect.disabled = true;
                    courseSelect.disabled = true;
                } else {
                    yearSelect.disabled = false;
                    courseSelect.disabled = false;
                }
            }

            // Initial call on page load
            toggleFieldsBasedOnRole();

            // Add event listener on role select change
            roleSelect.addEventListener('change', toggleFieldsBasedOnRole);
        });

        // Edit User Modal functionality
        document.addEventListener('DOMContentLoaded', function () {
            // Modal elements
            const editUserModal = document.getElementById('editUserModal');
            const editUserModalBackdrop = document.getElementById('editUserModalBackdrop');
            const closeEditUserModalBtn = document.getElementById('closeEditUserModalBtn');
            const cancelEditUserBtn = document.getElementById('cancelEditUserBtn');
            const editUserForm = document.getElementById('editUserForm');

            // Form selects
            const yearSelect = document.getElementById('edit-year');
            const courseSelect = document.getElementById('edit-course');
            const roleSelect = document.getElementById('editRole');

            // Show modal
            const showEditModal = () => {
                editUserModal.classList.add('show');
                editUserModalBackdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            };

            // Close modal
            const closeEditModal = () => {
                editUserModal.classList.remove('show');
                editUserModalBackdrop.classList.remove('show');
                document.body.style.overflow = '';
                resetEditForm();
            };

            // Reset form and clear validation
            const resetEditForm = () => {
                editUserForm.reset();
                clearEditValidationErrors();
            };

            const clearEditValidationErrors = () => {
                const errorFields = editUserForm.querySelectorAll('.invalid-feedback');
                errorFields.forEach(field => field.textContent = '');
            };

            // Event Listeners for modal close
            closeEditUserModalBtn.addEventListener('click', closeEditModal);
            cancelEditUserBtn.addEventListener('click', closeEditModal);
            editUserModalBackdrop.addEventListener('click', closeEditModal);

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && editUserModal.classList.contains('show')) {
                    closeEditModal();
                }
            });

            // Set select to N/A or empty value
            function setToNA(selectElement) {
                const naOption = Array.from(selectElement.options).find(opt => opt.value === "" || opt.text.toLowerCase() === "n/a");
                if (naOption) {
                    selectElement.value = naOption.value;
                } else {
                    selectElement.selectedIndex = 0;
                }
            }

            // Toggle year and course based on role and year values
            function toggleFieldsBasedOnRole() {
                const isAdmin = roleSelect.value === 'admin';

                if (isAdmin) {
                    setToNA(yearSelect);
                    setToNA(courseSelect);
                    yearSelect.disabled = true;
                    courseSelect.disabled = true;
                } else {
                    yearSelect.disabled = false;
                    // Enable course only if year is valid (not empty or 0 or "incoming")
                    courseSelect.disabled = !yearSelect.value || yearSelect.value === '0' || yearSelect.value.toLowerCase() === 'incoming';
                }
            }

            // Handle year change: disables/enables course accordingly
            function handleYearChange() {
                if (!yearSelect.value || yearSelect.value === '0' || yearSelect.value.toLowerCase() === 'incoming' || yearSelect.value === 'N/A') {
                    courseSelect.value = '';
                    courseSelect.disabled = true;
                } else if (roleSelect.value !== 'admin') {
                    courseSelect.disabled = false;
                }
            }

            // Listen for changes
            roleSelect.addEventListener('change', function() {
                toggleFieldsBasedOnRole();
            });

            yearSelect.addEventListener('change', function() {
                handleYearChange();
            });

            // Initial state on load
            toggleFieldsBasedOnRole();

            // Expose function to populate modal with user data and show modal
            window.populateEditModal = function (userId) {
                const userRow = document.querySelector(`.user-row[data-user-id="${userId}"]`);
                if (!userRow) return;

                document.getElementById('editName').value = userRow.querySelector('.user-name')?.textContent.trim() || '';
                document.getElementById('editEmail').value = userRow.querySelector('.user-email')?.textContent.trim() || '';
                document.getElementById('editRole').value = userRow.querySelector('.role-badge')?.textContent.trim().toLowerCase() || '';
                document.getElementById('editStatus').value = userRow.querySelector('.status-badge')?.textContent.trim().toLowerCase() || '';
                document.getElementById('edit-year').value = userRow.querySelector('.user-year-cell')?.getAttribute('data-year-id') || '';
                document.getElementById('edit-course').value = userRow.querySelector('.user-course-cell')?.getAttribute('data-course-id') || '';

                // Make sure controls are correctly enabled/disabled based on loaded role/year
                toggleFieldsBasedOnRole();
                handleYearChange();

                editUserForm.action = `/admin/user_crud/update/${userId}`;

                showEditModal();
            };
        });
    </script>

@endsection
